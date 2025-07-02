<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth:sanctum']], function()
{
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::post('/login', [UserController::class, 'login']);
Route::post('/signup', [UserController::class, 'signup']);
Route::get('/get_news', [NewsController::class, 'getNews']);
Route::get('/news/get', [NewsController::class, 'get']);
Route::get('/news/sources/get', [NewsController::class, 'getSources']);
Route::get('/news/authors/get', [NewsController::class, 'getAuthors']);

