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
            'status' => true,
            'message' => 'Data tiket berhasil diambil',
            'data' => $tickets
        ], 200);
    }

    // Create New Ticket
    public function store(Request $request)
    {
        // Validasi: nomor HP wajib angka & 10-15 digit
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'phone_number' => 'required|numeric|digits_between:10,15',
            'issue_description' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'customer_name' => $request->customer_name,
            'phone_number' => $request->phone_number,
            'issue_description' => $request->issue_description,
            'status' => 'pending'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tiket pengaduan berhasil dibuat',
            'data' => $ticket
        ], 201);
    }

    // Get Ticket Detail by ID
    public function show($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail tiket ditemukan',
            'data' => $ticket
        ], 200);
    }

    // Update Ticket Status / Data
    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        // Validasi opsional saat update jika nomor telepon ikut diubah
        $request->validate([
            'customer_name' => 'sometimes|required|string|max:100',
            'phone_number' => 'sometimes|required|numeric|digits_between:10,15',
            'issue_description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,process,completed',
        ]);

        $ticket->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Tiket berhasil diperbarui',
            'data' => $ticket
        ], 200);
    }

    // Delete Ticket
    public function destroy($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        $ticket->delete();

        return response()->json([
            'status' => true,
            'message' => 'Tiket berhasil dihapus'
        ], 200);
    }
}