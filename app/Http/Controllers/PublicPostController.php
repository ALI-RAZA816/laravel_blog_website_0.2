<?php

namespace App\Http\Controllers;

use App\Models\MonthlyViewsModel;
use App\Models\Post;
use App\Models\PostView;
use Illuminate\Http\Request;

class PublicPostController extends Controller
{
    public function publicPosts(Request $request)
    {
        $search_term = trim((string) $request->query('query'));

        // The list page does not show the post body, so we do not load "description" and "tags"
        $columns = ['id', 'title', 'image', 'date', 'category_id', 'author_id', 'views_counter', 'created_at'];

        $publicPost = Post::select($columns)
            ->with(['category:id,name,slug', 'author:id,name,image'])
            ->where('published', 'published')
            ->when($search_term !== '', fn ($q) => $q->titleSearch($search_term))
            ->latest()
            ->paginate(10);

        $popularPost = Post::select($columns)
            ->with(['category:id,name,slug', 'author:id,name,image'])
            ->where('published', 'published')
            ->orderBy('views_counter', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'allPost'     => $publicPost,
            'popularPost' => $popularPost,
        ], 200);
    }

    public function singleView(Request $request, int $id){
        $post = Post::with('category')->with('author:id,name,image')->where('published','published')->where('id',$id)->first();
        if(!$post){
            return response()->json([
                'messate'=>'Not found'
            ],404);
        }

        $user = $request->user();
        if($user){
            $viewed = PostView::where('user_id',$user->id)->where('post_id',$post->id)->exists();
            if(!$viewed){
                PostView::create([
                    'user_id'=>$user->id,
                    'post_id'=>$post->id
                ]);
                $post->increment('views_counter');
                $totalViews = Post::sum('views_counter');
                $previousMonth = now()->subMonth();
                $previousViews = MonthlyViewsModel::where('month', $previousMonth->month)->where('year', $previousMonth->year)->sum('snap_views') ?? 0;
                MonthlyViewsModel::updateOrCreate([
                    'month'=>now()->month,
                    'year'=>now()->year,
                ],[
                    'snap_views'=>$totalViews,
                    'monthly_views'=>$totalViews - $previousViews
                ]);
            }
        }
        return response()->json([
            'post'=>$post
        ],200);
    }
}
