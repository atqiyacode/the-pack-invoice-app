<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('app'); // A Blade view where Vue app is loaded
})->where('any', '.*'); // This catches all routes for Vue
