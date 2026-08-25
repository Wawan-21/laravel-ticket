<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiTicketController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ==========================================================
// ENDPOINT KHUSUS INTEGRASI N8N (SYARAT WAJIB TUGAS)
// ==========================================================
Route::get('/tickets/pending', [ApiTicketController::class, 'getPendingTickets']);
Route::post('/tickets/{id}/notify-status', [ApiTicketController::class, 'updateNotifyStatus']);

// ==========================================================
// ROUTE RESTful API CRUD TICKETS
// ==========================================================
Route::apiResource('tickets', ApiTicketController::class);