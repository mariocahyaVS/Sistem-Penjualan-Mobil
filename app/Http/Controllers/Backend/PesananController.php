<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;

// Tambahkan 3 baris ini di atas
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PesananExport;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Transaksi::with(['user', 'mobil'])->latest()->get();

        return view('backend.v_pesanan.index', [
            'judul' => 'Kelola Data Pesanan',
            'pesanan' => $pesanan
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    // --- TAMBAHKAN DUA FUNGSI INI DI BAWAH ---

    // Fungsi Cetak PDF
    public function cetakPdf()
    {
        $pesanan = Transaksi::with(['user', 'mobil'])->latest()->get();

        // Meload view pdf.blade.php yang sudah kita buat
        $pdf = Pdf::loadView('backend.v_pesanan.pdf', compact('pesanan'));

        // Setting kertas ke lanskap jika data banyak (Opsional)
        // $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan-Pesanan-Sigma-Automobil.pdf');
    }

    // Fungsi Ekspor Excel
    public function cetakExcel()
    {
        return Excel::download(new PesananExport, 'Laporan-Pesanan-Sigma-Automobil.xlsx');
    }
}
