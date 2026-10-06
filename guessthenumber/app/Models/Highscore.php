<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Highscore extends Model
{
    protected $table = 'highscore';
    protected $fillable = ['userid', 'score', 'attempts', 'time_taken'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}