<?php

namespace App\Http\Controllers;
use App\Models\Games as GamesModel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestGame = GamesModel::latest()->first();
        return view('home', compact('latestGame'));
    }
}
