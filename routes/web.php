<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire("/login", "pages::login")->name("login");

Route::get("/logout", function ()
{
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect("/");
});

Route::middleware("auth")->group(function ()
{
    Route::livewire("/", "pages::portal.home");
});
