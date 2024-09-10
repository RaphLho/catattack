<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Support\Facades\Log;

abstract class Controller
{
    public static function getScores(){
        return Score::orderByDesc('score')->get();
    }
}
