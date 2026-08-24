<?php

use Illuminate\Support\Facades\Route;

// Menampilkan halaman UI index.blade.php saat pertama kali diakses
Route::get('/', function () {
    return view('index');
});