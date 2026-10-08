<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');
Route::get('/dispatch', [HomeController::class, 'index']);