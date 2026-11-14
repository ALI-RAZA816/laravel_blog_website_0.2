<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function fetchComments(Request $request)
    {
        $user   = $request->user();
        $status = $request->query('query');
        $base = Comment::query()->when(Gate::denies('view-all-comments'),
            fn ($q) => $q->where('user_id', $user->id));

        $counts = (clone $base)->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        $comments = (clone $base)
            ->with(['user:id,name,email,image', 'post:id,title'])
            ->when($status && $status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()->paginate(5);

        return response()->json([
            'comments' => $comments,
            'counts'   => [
                'total'    => $counts->sum(),
                'pending'  => $counts['pending']  ?? 0,
                'approved' => $counts['approved'] ?? 0,
                'rejected' => $counts['rejected'] ?? 0,
                'spam'     => $counts['spam']     ?? 0,
            ],
        ]);
    }

    public function updateComment(Request $request, string $id)   
    {
        $data = $request->validate(['status' => 'required|in:pending,approved,rejected,spam']);

        $comment = Comment::find($id);
        if (!$comment) return response()->json(['message' => 'Comment not found'], 404);

        $comment->update(['status' => $data['status']]);
        return response()->json(['message' => 'Status updated to ' . $data['status']], 200);
    }

    public function fetchPostComments(int $id)   
    {
        $comments = Comment::where('post_id', $id)
            ->where('status', 'approved')                               
            ->whereHas('post', fn ($q) => $q->where('published', 'published'))   
            ->with('user:id,name,image')                                  
            ->latest()
            ->get(['id', 'user_id', 'comment', 'created_at']);

        return response()->json(['postComments' => $comments], 200);
    }
}
