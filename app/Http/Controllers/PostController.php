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
use Carbon\Carbon;


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
        if ($user) {
            $ids = $user->following()->pluck('users.id');
            $query->whereIn('user_id', $ids);
        }

        $usersWithPosts = User::has('posts')->inRandomOrder()->limit(6)->get();

        $posts = $query->simplePaginate(5);
        return view("post.index", [
            'posts' => $posts,
            'users' => $usersWithPosts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('post.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        // published_at: vom Browser (Europe/Berlin) → UTC in DB
        if (!empty($data['published_at'])) {
            // akzeptiert sowohl "2025-09-13T11:00" als auch "2025-09-13 11:45:21"
            $data['published_at'] = Carbon::parse($data['published_at'], 'Europe/Berlin')->utc();
        }


        $post = Post::create($data);

        $post->addMediaFromRequest('image')->toMediaCollection();
        // TinyMCE-Uploads vom User an den Post verschieben
        $user = Auth::user();
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
            'post' => $post,
            'comments' => $comments
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
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
        // Authorisierung
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validated();

        // published_at: Browser liefert lokale Zeit (Europe/Berlin) ohne TZ -> in UTC speichern
        if (array_key_exists('published_at', $data)) {
            if (!empty($data['published_at'])) {
                // akzeptiert "YYYY-MM-DDTHH:MM" (datetime-local) und "YYYY-MM-DD HH:MM:SS"
                $data['published_at'] = Carbon::parse($data['published_at'], 'Europe/Berlin')->utc();
            } else {
                // leeres Feld explizit als NULL speichern
                $data['published_at'] = null;
            }
        }

        // Update der Felder
        $post->update($data);

        // Bild nur anhängen, wenn tatsächlich eins hochgeladen wurde
        if ($request->hasFile('image')) {
            $post->addMediaFromRequest('image')->toMediaCollection();
        }

        // TinyMCE-Uploads vom User an den Post verschieben
        $user = Auth::user();
        $user->media()
            ->where('collection_name', 'tinymce-temp')
            ->each(function ($media) use ($post) {
                $media->update([
                    'model_type'      => Post::class,
                    'model_id'        => $post->id,
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
        if ($post->user_id !== Auth::id()) {
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

        if ($user) {
            $ids = $user->following()->pluck('users.id');
            $query->whereIn('user_id', $ids);
        }

        $posts = $query->simplePaginate(5);

        $usersWithPosts = User::has('posts')->inRandomOrder()->limit(6)->get();

        return view('post.index', [
            'posts' => $posts,
            'users' => $usersWithPosts
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
            'users' => User::has('posts')->inRandomOrder()->limit(6)->get()
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
