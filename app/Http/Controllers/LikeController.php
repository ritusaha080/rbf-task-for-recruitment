<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * @param Request $request
     * @param $postId
     * @return JsonResponse
     */
    public function likePost(Request $request, $postId)
    {
        $existingLike = Like::where('post_id', $postId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingLike) {
            return response()->json(['message' => 'You have already liked this post']);
        }

        Like::create([
            'post_id' => $postId,
            'user_id' =>  Auth::id(),
        ]);

        return response()->json(['message' => 'Post liked successfully']);
    }
}
