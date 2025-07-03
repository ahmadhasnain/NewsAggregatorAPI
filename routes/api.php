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

Route::post('/user/login', [UserController::class, 'login']);
Route::post('/user/signup', [UserController::class, 'signup']);
Route::get('/get_news', [NewsController::class, 'getNews']);
Route::get('/news/get', [NewsController::class, 'get']);
Route::get('/news/sources/get', [NewsController::class, 'getSources']);
Route::get('/news/authors/get', [NewsController::class, 'getAuthors']);
Route::get('artisan/{command}/{param}', [NewsController::class, 'fetch_news_command']); //if you want to run command using api

