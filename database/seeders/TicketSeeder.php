<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        Ticket::create([
            'customer_name' => 'Budi Santoso',
            'phone_number' => '081234567890',
            'issue_description' => 'Koneksi internet terputus dan tidak bisa akses server.',
            'status' => 'pending'
        ]);

        Ticket::create([
            'customer_name' => 'Siti Aminah',
            'phone_number' => '089876543210',
            'issue_description' => 'Aplikasi mengalami error saat tombol submit ditekan.',
            'status' => 'process'
        ]);
    }
}