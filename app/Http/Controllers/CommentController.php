<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        Comment::create([
            'body' => $request->input('comment'),
            'post_id' => $request->post_id,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', 'Kommentar gespeichert!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //dump($comment->user_id);
        // if($comment->user_id !== Auth::id()){
        //     abort(403);
        // }
        $comment->delete();
        return back()->with('success', 'Kommentar gelöscht!');
    }
}
