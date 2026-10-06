<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Games;

class AdminGames extends Controller
{
    public function index()
    {
        $games = Games::all();
        return view('admin.games', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.games.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'pegi' => 'required|string|max:10',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'image' => 'required|image|max:5120',
            'game_file' => 'required|file|mimes:zip|max:500000',
        ]);

        $validated['image'] = $request->file('image')->store('games', 'public');
        $validated['game_file'] = $request->file('game_file')->store('games', 'local');

        Games::create($validated);

        return redirect()->route('admin.games.index')->with('success', 'Game toegevoegd!');
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
        $game = Games::findOrFail($id);
        return view('admin.games.edit', compact('game'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $game = Games::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'pegi' => 'required|string|max:10',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'image' => [
                $game->image ? 'nullable' : 'required',
                'image',
                'max:5120',
            ],
            'game_file' => [
                $game->game_file ? 'nullable' : 'required',
                'file',
                'mimes:zip',
                'max:500000',
            ],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('games', 'public');
        }

        if ($request->hasFile('game_file')) {
            $validated['game_file'] = $request->file('game_file')->store('games', 'local');
        }

        $game->update($validated);

        return redirect()->route('admin.games.index')->with('success', 'Game geüpdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $game = Games::findOrFail($id);
        $game->delete();

        return redirect()->route('admin.games.index')->with('success', 'Game verwijderd!');
    }
}
