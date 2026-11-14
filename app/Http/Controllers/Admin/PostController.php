<?php

namespace App\Http\Controllers\Admin;

use App\Events\SubscribedUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\MonthlyReport;
use App\Models\Post;
use App\Models\PostView;
use App\Support\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function fetchPosts(Request $request)
    {

        $user  = $request->user();
        $term  = $request->query('query');
        $field = $request->query('field');
        $scope = fn ($q) => $q->when(Gate::denies('manage-all-posts'), fn ($q) => $q->where('author_id', $user->id));

        $posts = Post::select('id', 'title', 'image', 'category_id', 'author_id', 'published', 'date', 'views_counter', 'created_at')
        ->with(['category:id,name,slug', 'author:id,name,image'])
            ->tap($scope)
            ->when($term && $term !== 'all', function ($q) use ($term, $field) {
                match ($field) {
                    'search'   => $q->titleSearch($term),
                    'category' => $q->where('category_id', $term),
                    'status'   => $q->where('published', $term),
                    default    => null,
                };
            })
            ->latest()->paginate(10);

        $totalPosts = Post::tap($scope)->count();
        $thisMonth  = Post::tap($scope)
                  ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $velocity   = $totalPosts > 0 ? round(($thisMonth / $totalPosts) * 100, 2) : 0;

        $totalViews = PostView::count();
        $today = PostView::where('created_at', '>=', today())->count();
        $avgViews   = $totalViews > 0 ? round(($today / $totalViews) * 100, 2) : 0;

        return response()->json([
            'posts'        => $posts,
            'velocity'     => $velocity,
            'averageViews' => $avgViews,
            'this_month'   => MonthlyReport::select('month', 'year', 'total_post')->get(),
        ], 200);
    }

    public function addPost(StorePostRequest $request)
    {
        $imageName = ImageUpload::save($request->file('image'), 'posts-images');

        try {
            $post = DB::transaction(function () use ($request, $imageName) {
                $post = Post::create([
                    'title'       => $request->title,
                    'description' => $request->description,  
                    'category_id' => $request->category,
                    'published'   => $request->published,
                    'date'        => date('M d, y'),
                    'author_id'   => Auth::id(),
                    'tags'        => $request->tags,
                    'image'       => $imageName,
                ]);

                Category::where('id', $request->category)->increment('post_count');

                MonthlyReport::updateOrCreate(
                    ['user_id' => Auth::id(), 'month' => now()->month, 'year' => now()->year],
                    ['total_post' => Post::where('author_id', Auth::id())
                    ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count()]
                );
                return $post;
            });
        } catch (\Throwable $e) {
            ImageUpload::delete($imageName, 'posts-images');   
            throw $e;
        }

       if ($post->published === 'published') {
            SubscribedUser::dispatch($post);
        }

        return response()->json(['message' => 'Post published successfully'], 200);
    }

    public function fetchSingle(string $id)
    {
        $post = Post::with(['category:id,name,slug', 'author:id,name,image'])->find($id);
        if (!$post) return response()->json(['message' => 'Not found'], 404);

        Gate::authorize('view-post', $post);

        return response()->json(['post' => $post], 200);
    }

    public function editPost(UpdatePostRequest $request, string $id)
    {
       $post = Post::find($id);
        if (!$post) return response()->json(['message' => 'Not found'], 404);

        Gate::authorize('update-post', $post);

        $oldImage    = $post->image;

        $oldCategory = $post->category_id;
        $newImage    = $request->hasFile('image')
            ? ImageUpload::save($request->file('image'), 'posts-images')
            : null;

        try {
            DB::transaction(function () use ($request, $post, $newImage, $oldCategory) {
                $post->update([
                    'title'       => $request->title,
                    'description' => $request->description,
                    'published'   => $request->published,
                    'image'       => $newImage ?? $post->image,
                    'category_id' => $request->category,
                    'tags'        => $request->tags,
                ]);

                if ($oldCategory != $request->category) {
                    Category::where('id', $oldCategory)->decrement('post_count');
                    Category::where('id', $request->category)->increment('post_count');
                }
            });
        } catch (\Throwable $e) {
            ImageUpload::delete($newImage, 'posts-images');
            throw $e;
        }

        if ($newImage) ImageUpload::delete($oldImage, 'posts-images');   // purani image kaam hone ke BAAD delete

        return response()->json(['message' => 'Post updated successfully'], 200);
    }

    public function deletePost(Request $request, string $id)
    {
         $post = Post::find($id);
        if (!$post) return response()->json(['message' => 'Not found'], 404);

        Gate::authorize('delete-post', $post);

        DB::transaction(function () use ($post) {
            $post->delete();
            Category::where('id', $post->category_id)->decrement('post_count');
        });
        ImageUpload::delete($post->image, 'posts-images');

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    public function multiDeletePost(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|max:100',
            'ids.*' => 'integer|exists:posts,id',
        ]);

       $posts = Post::whereIn('id', $request->ids)->get();

        if ($posts->isEmpty()) return response()->json(['message' => 'Not found'], 404);

        foreach ($posts as $post) {
            Gate::authorize('delete-post', $post);  
        }


        DB::transaction(function () use ($posts) {
            foreach ($posts->groupBy('category_id') as $categoryId => $group) {
                Category::where('id', $categoryId)->decrement('post_count', $group->count());
            }
            Post::whereIn('id', $posts->pluck('id'))->delete();
        });

        foreach ($posts as $post) ImageUpload::delete($post->image, 'posts-images');

        return response()->json(['message' => 'Posts deleted successfully'], 200);
    }
}