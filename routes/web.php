<?php

use App\Http\Controllers\ClapController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PublicProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/@{user:username}', [PublicProfileController::class,'show'])->name('profile.show');
Route::get('/', [PostController::class, 'index'])->name('dashboard');
Route::get('/@{username}/{post:slug}', [PostController::class,'show'])->name('post.show');
Route::get('/category/{category}', [PostController::class, 'category'])->name('post.byCategory');
Route::post('/tinymce-upload', [PostController::class, 'tinymceUpload'])->name('tinymce.upload');


// Test routes
// Route::get('/check-proxy', function (\Illuminate\Http\Request $request) {
//     return response()->json([
//         'url' => $request->fullUrl(),
//         'secure' => $request->isSecure(),
//         'headers' => $request->headers->all(),
//     ]);
// });



Route::middleware('auth')->group(function () {

    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post/create', [PostController::class,'store'])->name('post.store');

    Route::get('/post/{post:slug}', [PostController::class,'edit'])->name('post.edit');
    Route::put('/post/{post}', [PostController::class,'update'])->name('post.update');

    Route::delete('/post/{post}', [PostController::class,'destroy'])->name('post.destroy');
    Route::get('/my-posts', [PostController::class,'myPosts'])->name('myPosts');
    Route::post('/follow/{user}', [FollowerController::class, 'followUnfollow'])->name('follow');
    Route::post('/clap/{post}', [ClapController::class, 'clap'])->name('clap');
    Route::post('/comment/create', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/comment/{comment}/destroy', [CommentController::class, 'destroy'])->name('comment.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';