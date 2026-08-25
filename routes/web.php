<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

// 1. Jalur Tampilan Utama (Menggunakan Controller)
Route::get('/', [TicketController::class, 'index']);

// 2. Jalur Web CRUD Laravel
Route::resource('tickets', TicketController::class)->except(['create', 'edit', 'show']);