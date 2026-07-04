<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Buat query dasar
        $baseQuery = Borrowing::query();

        // 2. Filter berdasarkan rentang tanggal (jika ada input dari user)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $baseQuery->whereBetween('borrow_date', [$request->start_date, $request->end_date]);
        }

        // 3. Hitung statistik untuk Kartu Ringkasan (berdasarkan filter tanggal)
        $totalBorrowings = (clone $baseQuery)->count();
        $totalReturned   = (clone $baseQuery)->where('status', 'returned')->count();
        $totalBorrowed   = (clone $baseQuery)->where('status', 'borrowed')->count();
        $totalPending    = (clone $baseQuery)->where('status', 'pending')->count();

        // 4. Filter tambahan berdasarkan status (jika dipilih di dropdown)
        $query = (clone $baseQuery)->with(['user', 'details.product'])->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 5. Ambil data dengan pagination & pertahankan parameter URL filter
        $borrowings = $query->paginate(15)->withQueryString();

        return view('reports.index', compact(
            'borrowings', 
            'totalBorrowings', 
            'totalReturned', 
            'totalBorrowed', 
            'totalPending'
        ));
    }
}