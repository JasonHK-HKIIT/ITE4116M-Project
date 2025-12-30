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

Route::name('portal.')->middleware('auth')->group(function ()
{
    Route::livewire('/', 'pages::portal.home')->name('home');
});

Route::name('dashboard.')->prefix('/dashboard')->middleware(['auth', 'role:admin'])->group(function ()
{
    Route::livewire('/', 'pages::dashboard.home')->name('home');

    Route::livewire('/news', 'pages::dashboard.news.list')->name('news.list');
    Route::livewire('/news/create', 'pages::dashboard.news.edit')->name('news.create');
    Route::livewire('/news/{id}', 'pages::dashboard.news.edit')->whereNumber('id')->name('news.edit');
});
