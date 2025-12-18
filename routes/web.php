<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'users.index');

Route::get("/logout", function ()
{
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect("/");
});
