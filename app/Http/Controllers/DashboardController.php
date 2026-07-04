<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Batas stok dianggap "menipis". Sesuaikan sendiri kalau perlu.
     */
    private const LOW_STOCK_THRESHOLD = 5;

    public function index()
    {
        // 1. Total Jenis Barang
        $totalBarang = Product::count();

        // 2. Total Fisik Barang Tersedia di Gudang
        $barangTersedia = Product::sum('stock');

        // 3. Total Fisik Barang yang sedang dibawa karyawan (dipinjam)
        // PENTING: status di sini pakai 'borrowed' (huruf kecil), sesuai
        // yang disimpan BorrowingController sekarang. Kalau masih pakai
        // 'Sedang Dipinjam', hasilnya akan selalu 0 karena gak akan pernah match.
        $barangDipinjam = BorrowingDetail::whereHas('borrowing', function ($query) {
            $query->where('status', 'borrowed');
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

        // 5. Peminjaman yang overdue (status borrowed tapi due_date sudah lewat)
        $overdueBorrowings = Borrowing::with(['user', 'details.product'])
            ->where('status', 'borrowed')
            ->whereDate('due_date', '<', now())
            ->latest('due_date')
            ->get();

        // 6. Produk dengan stok menipis
        $lowStockProducts = Product::where('stock', '<=', self::LOW_STOCK_THRESHOLD)
            ->orderBy('stock')
            ->get();

        // 7. User terbaru (buat panel Recent Users kalau ada)
        $recentUsers = User::with('role')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalBarang',
            'barangTersedia',
            'barangDipinjam',
            'chartData',
            'overdueBorrowings',
            'lowStockProducts',
            'recentUsers'
        ));
    }
}