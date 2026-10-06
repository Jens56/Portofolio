<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Games as GamesModel;
use Illuminate\Support\Facades\Storage;

class Games extends Controller
{
    public function index()
    {
        $games = GamesModel::all();
        return view('games', ['games' => $games]);
    }

    public function show($id)
    {
        $game = GamesModel::findOrFail($id);
        return view('game', ['game' => $game]);
    }

    public function download($id)
    {
        $game = GamesModel::findOrFail($id);
        return Storage::disk('local')->download($game->game_file);
    }
}