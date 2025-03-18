<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

// Route::get('/', function () {
//     dd(config('services.newsapi'));
//     // return view('welcome');
// });

Route::get('/news_article', [ArticleController::class, 'index']);