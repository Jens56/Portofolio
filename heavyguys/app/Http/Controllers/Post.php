<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Post extends Controller
{
    public function create()
    {
        return view('post');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $validatedData['author_id'] = Auth::id();

        BlogPost::create($validatedData);

        return redirect()->route('blog')->with('success', 'Post created successfully!');
    }
}
