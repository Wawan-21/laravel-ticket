<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::latest()->get();
        return view('index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'     => 'required|string|max:100',
            'phone_number'      => 'required|numeric|digits_between:10,15',
            'issue_description' => 'required|string|min:10',
        ], [
            'phone_number.numeric'  => 'Nomor WhatsApp wajib berupa angka!',
            'issue_description.min' => 'Deskripsi keluhan minimal 10 karakter!',
        ]);

        Ticket::create([
            'customer_name'     => $request->customer_name,
            'phone_number'      => $request->phone_number,
            'issue_description' => $request->issue_description,
            'status'            => 'pending'
        ]);

        return redirect()->back()->with('success', 'Tiket berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'customer_name'     => 'sometimes|required|string|max:100',
            'phone_number'      => 'sometimes|required|numeric|digits_between:10,15',
            'issue_description' => 'sometimes|required|string|min:10',
            'status'            => 'sometimes|required|in:pending,process,completed',
        ]);

        $ticket->update($request->all());

        return redirect()->back()->with('success', 'Tiket berhasil diperbarui');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->back()->with('success', 'Tiket berhasil dihapus');
    }
}