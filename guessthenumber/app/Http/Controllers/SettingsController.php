<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Highscore;


class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'countdown_seconds' => 'required|integer|min:0|max:3600',
            'attempts' => 'required|integer|min:0|max:100',
            'min_number' => 'required|integer|min:0|lt:max_number',
            'max_number' => 'required|integer|min:1|',
            'rounds' => 'required|integer|min:1|max:100',
        ], [
            'min_number.lt' => 'Minimum getal moet lager zijn dan maximum getal.',
        ]);

        $current = time();

        if ($validated['countdown_seconds'] > 0) {
            $validated['countdown_expires_at'] = $current + $validated['countdown_seconds'];
        } else {
            $validated['countdown_expires_at'] = null;
        }

        auth()->user()->update($validated);

        session()->forget('random_number');

        return redirect('/')->with('success', 'Settings updated successfully!');
    } 
    public function getCountdown()
    {
        $user = auth()->user();
        
        if ($user->rounds <= 0) {
            return response()->json(['time_remaining' => 0]);
        }
        
        if (!$user->countdown_expires_at) {
            return response()->json(['time_remaining' => 0]);
        }

        $expiresAt = $user->countdown_expires_at;
        $remaining = $expiresAt - time();
        
        if ($remaining < 0) {
            $user->update(['countdown_expires_at' => null]);
            return response()->json(['time_remaining' => 0]);
        }

        return response()->json(['time_remaining' => $remaining]);
    }

    public function settings()
    {
        return view('settings');
    }

    public function attemps()
    {
        return auth()->user()->attempts;
    }

    public function betweenNumbers()
    {
        $min = auth()->user()->min_number;
        $max = auth()->user()->max_number;

        if ($min >= $max) {
            return response()->json(['error' => 'Minimum getal moet kleiner zijn dan maximum getal'], 422);
        }

        return response()->json(['min' => $min, 'max' => $max]);
    }

    public function generateRandomNumber()
    {
        if (auth()->check()) 
        {
            $min = auth()->user()->min_number;
            $max = auth()->user()->max_number;
            return rand($min, $max);
        }
        return rand(1, 100);
    }

    public function randomNumber()
    {
        $user = auth()->user();
        
        if (session('game_finished')) 
        {
            session()->forget('game_finished');
            session()->forget('random_number');
            session()->forget('game_start_time');
            session()->forget('attempts_count');
        }
        
        if (!session()->has('random_number')) 
        {
            session(['random_number' => $this->generateRandomNumber()]);
            session(['game_start_time' => time()]);
            session(['attempts_count' => 1]);
        }
        
        if (session('game_finished')) {
            $randomNumber = 'Je hebt het getal goed geraden! Druk op restart knop om opnieuw te spelen.';
        } 
        else 
        {
            $randomNumber = session('random_number');
        }
        
        $timeRemaining = 0;
        
        if ($user->rounds > 0 && $user->countdown_expires_at) 
        {
            $remaining = $user->countdown_expires_at - time();
            $timeRemaining = max(0, $remaining);
        }

        $highscore = $this->getHighscore();
        
        $globalHighscores = Highscore::with('user')->orderBy('score', 'desc')->orderBy('time_taken', 'asc')->orderBy('attempts', 'asc')->limit(10)->get();
        
        $personalHighscores = Highscore::where('userid', $user->id)->orderBy('score', 'desc')->orderBy('time_taken', 'asc')->orderBy('attempts', 'asc')->limit(10)->get();

        return view('home', ['randomNumber' => $randomNumber, 'timeRemaining' => $timeRemaining, 'highscore' => $highscore,'min' => $user->min_number,'max' => $user->max_number,'globalHighscores' => $globalHighscores,'personalHighscores' => $personalHighscores]);
    }

    public function checkCorrectNumber(Request $request)
    {
        $user = auth()->user();
        $guess = (int)$request->input('guess');
        $randomNumber = (int)session('random_number');

        if(!$randomNumber)
        {
            return redirect('/')->with('hint', 'Geen getal beschikbaar! Refresh de pagina.');
        }

        if($guess == $randomNumber)
        {
            $gameStartTime = session('game_start_time') ?? time();
            $timeTaken = time() - $gameStartTime;
            $attemptsUsed = session('attempts_count', 1);
            
            $score = intval($user->rounds * ($user->max_number - $user->min_number));
            
            Highscore::create(['userid' => $user->id, 'attempts' => $attemptsUsed, 'score' => $score, 'time_taken' => $timeTaken]);
            
            session()->forget('random_number');
            session()->forget('game_start_time');
            session()->forget('attempts_count');
            
            $user->decrement('rounds');
            $user->refresh();
            
            if ($user->rounds <= 0) 
            {
                $user->update(['countdown_expires_at' => null]);
                session(['game_finished' => true]);
                return redirect('/')->with('correct', '🎉 Gefeliciteerd! Je hebt alle rondes voltooid! Het getal was ' . $randomNumber);
            }
            
            $user->update(['attempts' => $user->countdown_seconds > 0 ? 5 : 5]);
            
            session(['game_finished' => true]);
            return redirect('/')->with('correct', '🎉 Correct! Het getal was ' . $randomNumber . '. Rondes over: ' . $user->rounds);
        }
        else
        {
            session(['attempts_count' => session('attempts_count', 1) + 1]);
            $user->decrement('attempts'); 
            $user->refresh(); 
            
            if ($user->attempts <= 0) 
            {
                session(['game_finished' => true]);
                session()->forget('random_number');
                session()->forget('game_start_time');
                session()->forget('attempts_count');
                session(['game_finished' => true]);
                $user->update(['countdown_expires_at' => null]);
                return redirect('/')->with('hint', '❌ Geen pogingen meer over! Het getal was ' . $randomNumber . '. Druk op "Nieuw Spel" om de een nieuw spel te starten.');
            }
            
            if($guess < $randomNumber)
            {
                return redirect('/')->with('hint', 'Het getal is hoger! Pogingen over: ' . $user->attempts);
            }
            else
            {
                return redirect('/')->with('hint', 'Het getal is lager! Pogingen over: ' . $user->attempts);
            }
        } 
    }

    private function getHighscore()
    {
        $user = auth()->user();
        $rounds = $user->rounds;
        $max = $user->max_number;
        $min = $user->min_number;

        if ($rounds && $max && $min) 
        {
            return intval($rounds * ($max - $min));
        } 
        else 
        {
            return 0;
        }
    }

    public function highscore()
    {
        return response()->json(['highscore' => $this->getHighscore()]);
    }

    public function rounds()
    {
        $user = auth()->user();
        return response()->json(['rounds' => $user->rounds]);

        if ($user->rounds)
        {
            return response()->json(['rounds' => $user->rounds]);
        } 
        else 
        {
            return response()->json(['rounds' => 1]);
        }
    }

    public function stopGame()
    {
        if (session('countdown_expires_at'))
        {
            session()->forget('countdown_expires_at');
            session()->forget('random_number');
            session()->forget('game_start_time');
            session()->forget('attempts_count');
            session(['game_finished' => true]);
            return redirect('/')->with('hint', 'Spel gestopt! Druk op "Nieuw Spel" om opnieuw te spelen.');
        }
    }
}