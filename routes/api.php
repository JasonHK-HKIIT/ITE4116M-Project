<?php

use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\StudentActivitiesController;
use Illuminate\Support\Facades\Route;

Route::get('/news', [NewsController::class, 'index']);
Route::get('/activities', [StudentActivitiesController::class, 'index']);
