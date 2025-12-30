<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire('/login', 'pages::login')->name('login');

Route::get('/logout', function ()
{
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
});

Route::middleware('auth')->group(function ()
{
    Route::livewire('/', 'pages::portal.home')->name('portal.home');

    Route::livewire('/news', 'pages::portal.news.list');
    Route::livewire('/news/{id}', 'pages::portal.news.view');

    Route::middleware('role:admin')->prefix('/dashboard')->group(function ()
    {
        Route::livewire('/', 'pages::dashboard.home');
    });
});
