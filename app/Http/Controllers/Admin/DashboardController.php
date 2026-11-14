<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\MonthlyViewsModel;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function DashboardAnalysis(Request $request)
    {
        $user     = $request->user();
        $onlyOwn = Gate::denies('manage-all-posts');

        $posts    = Post::query()->when($onlyOwn, fn ($q) => $q->where('author_id', $user->id));
        $comments = Comment::query()->when($onlyOwn, fn ($q) =>
                        $q->whereHas('post', fn ($p) => $p->where('author_id', $user->id)));

        $totalComments = (clone $comments)->count();
        $monthRange    = [now()->startOfMonth(), now()->endOfMonth()];
        $commentsMonth = (clone $comments)->whereBetween('created_at', $monthRange)->count();

        $totalPosts = (clone $posts)->count();
        $postsMonth    = (clone $posts)->whereBetween('created_at', $monthRange)->count();

        return response()->json([
            'total_posts'     => $totalPosts,
            'total_comments'  => $totalComments,
            'total_users'     => Gate::allows('manage-users') ? User::count() : null,
            'views'           => (clone $posts)->sum('views_counter'),
            'average'         => $totalComments > 0 ? round(($commentsMonth / $totalComments) * 100, 2) : 0,
            'velocity'        => $totalPosts > 0 ? round(($postsMonth / $totalPosts) * 100, 2) : 0,
            'recent_posts'    => (clone $posts)->with(['category:id,name,slug', 'author:id,name,image'])
                                    ->latest()->take(5)
                                    ->get(['id', 'title', 'category_id', 'author_id', 'published', 'date']),
            'recent_comments' => (clone $comments)->with('user:id,name,image')
                                    ->latest()->take(5)->get(['id', 'user_id', 'comment']),
            'last_month'      => MonthlyViewsModel::select('month', 'year', 'monthly_views')->get(),
        ], 200);
    }
}
