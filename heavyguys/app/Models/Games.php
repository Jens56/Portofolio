<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    protected $fillable = [
        'name',
        'genre',
        'pegi',
        'price',
        'description',
        'image',
        'game_file',
    ];
}