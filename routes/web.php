<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire('/login', 'pages::login')->name('login');

Route::middleware('auth')->group(function ()
{
    Route::get('/logout', function ()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');

    Route::livewire('/password', 'pages::password')->name('password');
});

Route::name('portal.')->middleware('auth')->group(function ()
{
    Route::livewire('/', 'pages::portal.home')->name('home');

    Route::livewire('/assistant', 'pages::portal.assistant')->name('assistant');
    Route::livewire('/assistant/{id}', 'pages::portal.assistant')->whereUuid('id')->name('assistant.thread');

    Route::livewire('/activities', 'pages::portal.activities.list')->name('activities.list');
    Route::livewire('/activities/show/{id}', 'pages::portal.activities.show')->whereNumber('id')->name('activities.show');
    Route::livewire('/activities/register/{id}', 'pages::portal.activities.register')->whereNumber('id')->name('activities.register');
    Route::livewire('/activities/unregister/{id}', 'pages::portal.activities.unregister')->whereNumber('id')->name('activities.unregister');

    Route::livewire('/profile/personal-particular', 'pages::portal.profile.personal-particular')->name('profile.personal-particular');
    Route::livewire('/profile/programme-modules', 'pages::portal.profile.programme-modules')->name('profile.programme-modules');

    Route::livewire('/resources/resources-centre', 'pages::portal.resources.resources-centre')->name('resources-centre');

    Route::livewire('/news', 'pages::portal.news.list')->name('news.list');
    Route::livewire('/news/{article:slug}', 'pages::portal.news.view')->name('news.view');

    Route::livewire('/calendar', 'pages::portal.calendar.calendar_view')->name('calendar');
});

Route::name('dashboard.')->prefix('/dashboard')->middleware(['auth', 'role:admin,staff'])->group(function ()
{
    Route::livewire('/', 'pages::dashboard.home')->name('home');

    Route::middleware('permission:calendar')->group(function ()
    {
        Route::livewire('/calendar', 'pages::dashboard.calendar.manage')->name('calendar.manage');
        Route::livewire('/calendar/create', 'pages::dashboard.calendar.events')->name('calendar.events');
        Route::livewire('/calendar/classes', 'pages::dashboard.calendar.management.classes')->name('calendar.classes');
        Route::livewire('/calendar/activities', 'pages::dashboard.calendar.management.activities')->name('calendar.activities');
        Route::livewire('/calendar/institute-holidays', 'pages::dashboard.calendar.management.institute_holidays')->name('calendar.institute_holidays');
        Route::livewire('/calendar/public-holidays', 'pages::dashboard.calendar.management.public_holidays')->name('calendar.public_holidays');
    });

    Route::middleware('permission:academic')->group(function ()
    {
        Route::livewire('/academic/institutes', 'pages::dashboard.academic.institutes')->name('academic.institutes');
        Route::livewire('/academic/campuses', 'pages::dashboard.academic.campuses')->name('academic.campuses');
        Route::livewire('/academic/programmes', 'pages::dashboard.academic.programmes.list')->name('academic.programmes.list');
        Route::livewire('/academic/programmes/create', 'pages::dashboard.academic.programmes.edit')->name('academic.programmes.create');
        Route::livewire('/academic/programmes/{programme}', 'pages::dashboard.academic.programmes.edit')->whereNumber('programme')->name('academic.programmes.edit');
        Route::livewire('/academic/modules', 'pages::dashboard.academic.modules.list')->name('academic.modules.list');
        Route::livewire('/academic/modules/create', 'pages::dashboard.academic.modules.edit')->name('academic.modules.create');
        Route::livewire('/academic/modules/{module}', 'pages::dashboard.academic.modules.edit')->whereNumber('module')->name('academic.modules.edit');
        Route::livewire('/academic/classes', 'pages::dashboard.academic.classes.list')->name('academic.classes.list');
        Route::livewire('/academic/classes/create', 'pages::dashboard.academic.classes.edit')->name('academic.classes.create');
        Route::livewire('/academic/classes/{class}', 'pages::dashboard.academic.classes.edit')->whereNumber('class')->name('academic.classes.edit');
    });

    Route::middleware('permission:students')->group(function ()
    {
        Route::livewire('/students', 'pages::dashboard.students.list')->name('students.list');
        Route::livewire('/students/create', 'pages::dashboard.students.edit')->name('students.create');
        Route::livewire('/students/{user}', 'pages::dashboard.students.edit')->whereNumber('user')->name('students.edit');
    });

    Route::middleware('permission:news')->group(function ()
    {
        Route::livewire('/news', 'pages::dashboard.news.list')->name('news.list');
        Route::livewire('/news/create', 'pages::dashboard.news.edit')->name('news.create');
        Route::livewire('/news/{article}', 'pages::dashboard.news.edit')->whereNumber('article')->name('news.edit');
    });

    Route::middleware('permission:resources')->group(function ()
    {
        Route::livewire('/resources', 'pages::dashboard.resources.list')->name('resources.list');
        Route::livewire('/resources/create', 'pages::dashboard.resources.edit')->name('resources.create');
        Route::livewire('/resources/{resource}', 'pages::dashboard.resources.edit')->whereNumber('resource')->name('resources.edit');
    });

    Route::middleware('permission:activities')->group(function ()
    {
        Route::livewire('/activities', 'pages::dashboard.activities.list')->name('activities.list');
        Route::livewire('/activities/create', 'pages::dashboard.activities.edit')->name('activities.create');
        Route::livewire('/activities/{activity}', 'pages::dashboard.activities.edit')->whereNumber('activity')->name('activities.edit');
    });

    Route::middleware('role:admin')->group(function ()
    {
        Route::livewire('/system/staff', 'pages::dashboard.system.staff.list')->name('system.staff.list');
        Route::livewire('/system/staff/create', 'pages::dashboard.system.staff.edit')->name('system.staff.create');
        Route::livewire('/system/staff/{staff}', 'pages::dashboard.system.staff.edit')->whereNumber('staff')->name('system.staff.edit');
        Route::livewire('/system/password', 'pages::dashboard.system.password')->name('system.password');
    });

});
