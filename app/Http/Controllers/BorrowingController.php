<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function index()
    {
        // Menampilkan riwayat peminjaman beserta detail barangnya
        $borrowings = Borrowing::with(['user', 'details.product'])->latest()->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        // Hanya tampilkan barang yang stoknya lebih dari 0
        $products = Product::where('stock', '>', 0)->get();
        return view('borrowings.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
            'borrow_date' => 'required|date',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->qty) {
            return back()->withErrors(['qty' => 'Stok barang tidak mencukupi.'])->withInput();
        }

        // Gunakan Transaction agar jika gagal di tengah jalan, database kembali utuh
        DB::transaction(function () use ($request, $product) {
            // 1. Buat Header Peminjaman
            $borrowing = Borrowing::create([
                'user_id' => Auth::id(),
                'borrow_date' => $request->borrow_date,
                'status' => 'Sedang Dipinjam',
            ]);

            // 2. Buat Detail Barang yang dipinjam
            BorrowingDetail::create([
                'borrowing_id' => $borrowing->id,
                'product_id' => $product->id,
                'qty' => $request->qty,
                'item_status' => 'Baik', 
            ]);

            // 3. Kurangi Stok Barang Fisik
            $product->decrement('stock', $request->qty);
        });

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function returnItem(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status === 'Selesai') {
            return back()->with('error', 'Barang ini sudah dikembalikan sebelumnya.');
        }

        $request->validate([
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'item_status' => 'required|string', // Misal: Baik, Rusak
        ]);

        DB::transaction(function () use ($request, $borrowing) {
            // 1. Update status Peminjaman
            $borrowing->update([
                'return_date' => $request->return_date,
                'status' => 'Selesai',
            ]);

            // 2. Kembalikan stok dan update kondisi barang di tabel detail
            foreach ($borrowing->details as $detail) {
                $detail->update(['item_status' => $request->item_status]);
                
                $product = Product::find($detail->product_id);
                $product->increment('stock', $detail->qty);
            }
        });

        return redirect()->route('borrowings.index')->with('success', 'Barang berhasil dikembalikan.');
    }
}