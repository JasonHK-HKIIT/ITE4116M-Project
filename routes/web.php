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

    Route::livewire('/assistant', 'pages::portal.assistant')->name('assistant');
    Route::livewire('/assistant/{id}', 'pages::portal.assistant')->whereUuid('id')->name('assistant.thread');

    Route::livewire('/activities', 'pages::portal.activities.list')->name('activities.list');
    Route::livewire('/activities/show/{id}', 'pages::portal.activities.show')->whereNumber('id')->name('activities.show');

    Route::livewire('/profile/personal-particular', 'pages::portal.profile.personal-particular')->name('profile.personal-particular');
    Route::livewire('/profile/programme-modules', 'pages::portal.profile.programme-modules')->name('profile.programme-modules');

    Route::livewire('/resources/resources-centre', 'pages::portal.resources.resources-centre')->name('resources-centre');

    Route::livewire('/news', 'pages::portal.news.list')->name('news.list');
    Route::livewire('/news/{article:slug}', 'pages::portal.news.view')->name('news.view');

    Route::livewire('/calendar', 'pages::portal.calendar.calendar_view')->name('calendar');
});

Route::name('dashboard.')->prefix('/dashboard')->middleware(['auth', 'role:admin'])->group(function ()
{
    Route::livewire('/', 'pages::dashboard.home')->name('home');

    Route::livewire('/academic/institutes', 'pages::dashboard.academic.institutes')->name('academic.institutes');
    Route::livewire('/academic/campuses', 'pages::dashboard.academic.campuses')->name('academic.campuses');

    Route::livewire('/news', 'pages::dashboard.news.list')->name('news.list');
    Route::livewire('/news/create', 'pages::dashboard.news.edit')->name('news.create');
    Route::livewire('/news/{article}', 'pages::dashboard.news.edit')->whereNumber('article')->name('news.edit');

    Route::livewire('/resources', 'pages::dashboard.resources.list')->name('resources.list');
    Route::livewire('/resources/create', 'pages::dashboard.resources.edit')->name('resources.create');
    Route::livewire('/resources/{resource}', 'pages::dashboard.resources.edit')->whereNumber('resource')->name('resources.edit');

    Route::livewire('/activities', 'pages::dashboard.activities.list')->name('activities.list');
    Route::livewire('/activities/create', 'pages::dashboard.activities.edit')->name('activities.create');
    Route::livewire('/activities/{activity}', 'pages::dashboard.activities.edit')->whereNumber('activity')->name('activities.edit');
});
