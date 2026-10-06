<?php

namespace App\Http\Controllers;

use App\Models\Reaction as ReactionModel;
use Illuminate\Http\Request;

class Reaction extends Controller
{
    public function store(Request $request, $postId)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $validatedData['blog_post_id'] = $postId;
        $validatedData['user_id'] = auth()->id();

        ReactionModel::create($validatedData);

        return redirect()->route('blog.show', $postId)->with('success', 'Reactie geplaatst!');
    }

    public function destroy($id)
    {
        $reaction = ReactionModel::findOrFail($id);
        
        if ($reaction->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Je kunt alleen je eigen reacties verwijderen.');
        }
        
        $postId = $reaction->blog_post_id;
        $reaction->delete();

        return redirect()->route('blog.show', $postId)->with('success', 'Reactie verwijderd!');
    }
}
