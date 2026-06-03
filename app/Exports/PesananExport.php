<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PesananExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Mengambil data pesanan beserta relasinya
        return Transaksi::with(['user', 'mobil'])->latest()->get();
    }

    // Menentukan judul kolom di baris pertama Excel
    public function headings(): array
    {
        return [
            'Kode Booking',
            'Tanggal Pesan',
            'Nama Pelanggan',
            'No. HP',
            'Unit Mobil',
            'Booking Fee (Rp)',
            'Status'
        ];
    }

    // Memetakan data dari database ke kolom Excel
    public function map($transaksi): array
    {
        return [
            $transaksi->kode_booking,
            $transaksi->created_at->format('d-m-Y H:i'),
            $transaksi->user->nama ?? '-',
            $transaksi->no_hp,
            $transaksi->mobil->nama_mobil ?? '-',
            $transaksi->booking_fee,
            $transaksi->status
        ];
    }
}
