<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ApiTicketController extends Controller
{
    // Get All Tickets
    public function index()
    {
        $tickets = Ticket::all();
        return response()->json([
            'status'  => true,
            'message' => 'Data tiket berhasil diambil',
            'data'    => $tickets
        ], 200);
    }

    // Create New Ticket
    public function store(Request $request)
    {
        // Validasi: No WA angka & deskripsi min 10 karakter
        $request->validate([
            'customer_name'     => 'required|string|max:100',
            'phone_number'      => 'required|numeric|digits_between:10,15',
            'issue_description' => 'required|string|min:10', // Sudah ditambah min:10
        ], [
            'phone_number.numeric'  => 'Nomor WhatsApp wajib berupa angka!',
            'issue_description.min' => 'Deskripsi keluhan minimal 10 karakter!',
        ]);

        $ticket = Ticket::create([
            'customer_name'     => $request->customer_name,
            'phone_number'      => $request->phone_number,
            'issue_description' => $request->issue_description,
            'status'            => 'pending'
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Tiket pengaduan berhasil dibuat',
            'data'    => $ticket
        ], 201);
    }

    // Get Ticket Detail by ID
    public function show($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status'  => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail tiket ditemukan',
            'data'    => $ticket
        ], 200);
    }

    // Update Ticket Status / Data
    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status'  => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'customer_name'     => 'sometimes|required|string|max:100',
            'phone_number'      => 'sometimes|required|numeric|digits_between:10,15',
            'issue_description' => 'sometimes|required|string|min:10', // Sudah ditambah min:10
            'status'            => 'sometimes|required|in:pending,process,completed',
        ]);

        $ticket->update($request->all());

        return response()->json([
            'status'  => true,
            'message' => 'Tiket berhasil diperbarui',
            'data'    => $ticket
        ], 200);
    }

    // Delete Ticket
    public function destroy($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status'  => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        $ticket->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Tiket berhasil dihapus'
        ], 200);
    }

    // ==========================================================
    // ENDPOINT KHUSUS INTEGRASI N8N (SYARAT WAJIB TUGAS)
    // ==========================================================

    // GET /api/tickets/pending
    public function getPendingTickets()
    {
        $tickets = Ticket::where('status', 'pending')->get();
        return response()->json([
            'status'  => true,
            'message' => 'Daftar tiket pending berhasil diambil',
            'data'    => $tickets
        ], 200);
    }

    // POST /api/tickets/{id}/notify-status
    public function updateNotifyStatus($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status'  => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        $ticket->status = 'process';
        $ticket->save();

        return response()->json([
            'status'  => true,
            'message' => 'Status tiket berhasil diperbarui menjadi process oleh n8n',
            'data'    => $ticket
        ], 200);
    }
}