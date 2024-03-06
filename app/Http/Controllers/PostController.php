<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * @param PostRequest $request
     * @return JsonResponse
     */
    public function postCreate(PostRequest $request)
    {
        try {
            $imageNames = [];
            if ($request->file('images')) {
                foreach ($request->file('images') as $key => $image) {
                    $newName = rand() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('post-images'), $newName);
                    $imageNames[] = $newName;
                }
            }

            $post = Post::create([
                'user_id' => Auth::id(),
                'description' => $request->input('description'),
                'images' => json_encode($imageNames),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Your post has been created successfully',
                'data' => [
                    'post' => $post
                ]
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => __('Something went wrong! Please try again: ') . $exception->getMessage(),
                'data' => null
            ]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function postList()
    {
        $postList = Post::with([
            'comments' => function ($query) {
                $query->select('id', 'user_id', 'comments', 'post_id');
            },
            'comments.user' => function ($query) {
                $query->select('id', 'name');
            },
            'likes' => function ($query) {
                $query->select('id', 'user_id', 'post_id');
            },
            'likes.user' => function ($query) {
                $query->select('id', 'name');
            },
        ])
            ->select('id', 'user_id', 'description', 'images')
            ->paginate(10);

        $postList->each(function ($post) {
            $post->likes_count = Like::where('post_id', $post->id)->count();
            $post->comments_count = Comment::where('post_id', $post->id)->count();
        });

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => [
                'postList' => $postList
            ]
        ]);
    }
}
