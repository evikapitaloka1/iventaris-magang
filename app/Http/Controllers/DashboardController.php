<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Jenis Barang
        $totalBarang = Product::count();
        
        // 2. Total Fisik Barang Tersedia di Gudang
        $barangTersedia = Product::sum('stock');
        
        // 3. Total Fisik Barang yang sedang dibawa karyawan (dipinjam)
        $barangDipinjam = BorrowingDetail::whereHas('borrowing', function($query) {
            $query->where('status', 'Sedang Dipinjam');
        })->sum('qty');

        // 4. Data untuk Grafik Peminjaman per Bulan (Chart.js)
        $grafikBulanan = Borrowing::select(
                DB::raw('EXTRACT(MONTH FROM borrow_date) as bulan'), 
                DB::raw('COUNT(id) as total')
            )
            ->where(DB::raw('EXTRACT(YEAR FROM borrow_date)'), date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Format array agar selalu 12 bulan
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $grafikBulanan[$i] ?? 0;
        }

        return view('dashboard', compact('totalBarang', 'barangTersedia', 'barangDipinjam', 'chartData'));
    }
}