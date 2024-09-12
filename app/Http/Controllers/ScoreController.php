<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Score;
use Illuminate\Support\Str;

class ScoreController extends Controller
{
    
    public static function getScores(){
        return Score::orderByDesc('score')->get()->groupBy('level');
    }

    public function saveScore(Request $data){
        return Score::insert(['name'=>$data["name"],'score'=>$data['score'], 'level'=>$data['level']]);
    }
}
