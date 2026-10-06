<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class Blog extends Controller
{
    public function index()
    {
        $posts = BlogPost::latest()->get();
        return view('blog', compact('posts'));
    }

    public function show($id)
    {
        $post = BlogPost::with('reactions')->findOrFail($id);
        return view('blog.post', compact('post'));
    }

    public function delete($id)
    {
        $post = BlogPost::findOrFail($id);
        
        if ($post->author_id !== auth()->id()) {
            return redirect()->route('blog')->with('error', 'Je kunt alleen je eigen berichten verwijderen.');
        }
        
        $post->delete();

        return redirect()->route('blog')->with('success', 'Bericht succesvol verwijderd!');
    }
}
