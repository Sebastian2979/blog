<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Post::with(['user', 'media'])
            ->withCount('claps')
            ->where('published_at', '<=', now())
            ->latest();

        $user = Auth::user();
        if($user){
            $ids = $user->following()->pluck('users.id');
            $query->whereIn('user_id', $ids);
        }


        $posts = $query->simplePaginate(5);
        return view("post.index", [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('post.create', [
            'categories'=> $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $post = Post::create($data);
        $post->addMediaFromRequest('image')->toMediaCollection();
        // TinyMCE-Uploads vom User an den Post verschieben
        $user = User::find(Auth::id());
        $user->media()
            ->where('collection_name', 'tinymce-temp')
            ->each(function ($media) use ($post) {
                $media->update([
                    'model_type' => Post::class,
                    'model_id' => $post->id,
                    'collection_name' => 'content-images',
                ]);
            });
        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $username, Post $post)
    {
        $comments = $post->comments()->with('user')->latest()->get();
        
        return view('post.show', [
            'post'=> $post,
            'comments' => $comments
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if($post->user_id !== Auth::id()){
            abort(403);
        }
        
        $categories = Category::get();
        return view('post.edit', [
            'post' => $post,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        if($post->user_id !== Auth::id()){
            abort(403);
        }
        $data = $request->validated();
        $post->update($data);
        if($data['image'] ?? false){
            $post->addMediaFromRequest('image')->toMediaCollection();
        }
        // TinyMCE-Uploads vom User an den Post verschieben
        $user = User::find(Auth::id());
        $user->media()
            ->where('collection_name', 'tinymce-temp')
            ->each(function ($media) use ($post) {
                $media->update([
                    'model_type' => Post::class,
                    'model_id' => $post->id,
                    'collection_name' => 'content-images',
                ]);
            });
        return redirect()->route('myPosts');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if($post->user_id !== Auth::id()){
            abort(403);
        }
        $post->delete();
        return redirect()->route('dashboard');
    }

    public function category(Category $category)
    { 
        $user = Auth::user();

        $query = $category->posts()
            ->with(['user', 'media'])
            ->where('published_at', '<=', now())
            ->withCount('claps')
            ->latest();

        if($user){
            $ids = $user->following()->pluck('users.id');
            $query->whereIn('user_id', $ids);
        }

        $posts = $query->simplePaginate(5);    

        return view('post.index', [
            'posts' => $posts,
        ]);
    }

    public function myPosts()
    {
        $user = Auth::user();
        $posts = $user->posts()
            ->with(['user', 'media'])
            ->withCount('claps')
            ->latest()
            ->simplePaginate(5);

        return view('post.index', [
            'posts' => $posts,
        ]);
    }

    public function tinymceUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048', // Max. 2MB
        ]);
       
        // Bild dem User zuordnen
        $media = $request->user()->addMediaFromRequest('file')->toMediaCollection('tinymce-temp');
        
        return response()->json([
            'location' => $media->getUrl()
        ], 200);
    }
}
