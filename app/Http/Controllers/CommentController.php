<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * @param CommentRequest $request
     * @param $postId
     * @return JsonResponse
     */
    public function commentOnPost(CommentRequest $request, $postId)
    {
        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'comments' => $request->post('comments'),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment added successfully',
            'data' => [
                'comment' => $comment
            ]
        ]);
    }

    /**
     * @param CommentRequest $request
     * @param $commentId
     * @return JsonResponse
     */
    public function editComment(CommentRequest $request, $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->comments = $request->post('comments');
        $comment->save();

        return response()->json([
            'status' => true,
            'message' => 'Comment has been updated successfully!',
            'data' => null
        ]);
    }

    /**
     * @param $commentId
     * @return JsonResponse
     */
    public function deleteComment($commentId){
        $comment = Comment::findOrFail($commentId);
        $comment->delete();
        return response()->json([
            'status' => true,
            'message' => 'Comment has been deleted successfully!',
            'data' => null
        ]);
    }
}
