<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\Score;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/game/level', function () {
    return view('game.level');
});

Route::get('/skins', function () {
    return view('skins');
});

Route::get('/game/leaderboard', function () {
    $board = Controller::getScores();
    return view('game.leaderboard', ["board"=> $board]);
});

Route::get('/levels/SelectLevels', function () {
    return view('levels.SelectLevels');
});





Route::get('/1', function () {
    return view('levels.1');
});

Route::get('/2', function () {
    return view('levels.2');
});

Route::get('/3', function () {
    return view('levels.3');
});

Route::get('/4', function () {
    return view('levels.4');
});

Route::get('/5', function () {
    return view('levels.5');
});

Route::get('/6', function () {
    return view('levels.6');
});

Route::get('/7', function () {
    return view('levels.7');
});

Route::get('/8', function () {
    return view('levels.8');
});

Route::get('/9', function () {
    return view('levels.9');
});

Route::get('/10', function () {
    return view('levels.10');
});