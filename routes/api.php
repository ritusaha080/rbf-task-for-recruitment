<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
    Route::post('sign-in', [UserController::class, 'login'])->name('api.Sign-In');
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('post-create', [PostController::class, 'postCreate'])->name('api.post-create');
    Route::get('post-list', [PostController::class, 'postList'])->name('api.post-list');
    Route::post('like-post/{postId}', [LikeController::class, 'likePost'])->name('api.like.post');
    Route::post('comment-on-post/{postId}', [CommentController::class, 'commentOnPost'])->name('api.comment.on.post');
    Route::post('edit-comment-on-post/{commentId}', [CommentController::class, 'editComment'])->name('api.edit.comment.on.post');
    Route::delete('delete-comment/{commentId}', [CommentController::class, 'deleteComment'])->name('api.delete.comment.on.post');

});


