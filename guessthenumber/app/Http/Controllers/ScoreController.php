<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Highscore;

class ScoreController extends Controller
{
    public function index()
    {
        $highscores = Score::orderBy('score', 'desc')->limit(10)->get();
        
        return view('scores.index', ['highscores' => $highscores]);
    }

    public function show($id)
    {
        $highscore = Highscore::findOrFail($id);
        
        return view('scores.show', ['highscore' => $highscore]);
    }
    
    public function destroy($id)
    {
        $highscore = Highscore::findOrFail($id);

        if ($highscore->userid !== auth()->id()) {
            return redirect('/')->with('hint', '❌ Je kunt alleen je eigen scores verwijderen!');
        }

        $highscore->delete();

        return redirect('/')->with('correct', 'Verwijderen');
    }
}