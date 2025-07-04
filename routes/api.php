<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth:sanctum']], function()
{
    Route::get('/user/logout', [UserController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::group(['prefix' => 'user'], function(){
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/signup', [UserController::class, 'signup']);
});

Route::group(['prefix' => 'news'], function(){
    Route::get('/get', [NewsController::class, 'get']);
    Route::get('/sources/get', [NewsController::class, 'getSources']);
    Route::get('/authors/get', [NewsController::class, 'getAuthors']);
});
Route::get('/get_news', [NewsController::class, 'getNews']);

Route::get('artisan/{command}/{param}', [NewsController::class, 'fetch_news_command']); //if you want to run command using api

