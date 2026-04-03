<?php

use App\Http\Controllers\Api\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/news', [NewsController::class, 'index']);
