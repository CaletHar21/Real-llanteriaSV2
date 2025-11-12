<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); // o eliminá ambas si no usás vistas
});
