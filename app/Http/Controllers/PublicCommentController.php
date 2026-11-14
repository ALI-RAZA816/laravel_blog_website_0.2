<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class PublicCommentController extends Controller
{
    public function addComment(StoreCommentRequest $request)
    {
        $post = Post::select('id', 'title')->findOrFail($request->post_id);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'comment' => $request->comment,
            'on_post' => $post->title,
            'date'    => date('M d, y'),
        ]);

        return response()->json(['message' => 'Comment added. It will appear after approval.'], 200);
    }

    public function updateComment(UpdateCommentRequest $request, int $id)
    {
        $comment = Comment::find($id);
        if (!$comment) return response()->json(['message' => 'Comment not found'], 404);

        Gate::authorize('update-comment', $comment);

        $comment->update(['comment' => $request->comment, 'status' => 'pending']);
        return response()->json(['message' => 'Comment updated successfully'], 200);
    }

    public function deleteComment(int $id)
    {
        $comment = Comment::find($id);
        if (!$comment) return response()->json(['message' => 'Comment not found'], 404);

        Gate::authorize('delete-comment', $comment);

        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
