<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Product;
use App\Models\User; // Tambahan untuk memanggil data Admin
use App\Notifications\NewPeminjamanNotification; // Tambahan class notifikasi admin
use App\Notifications\BorrowingStatusUpdatedNotification; // Tambahan class notifikasi staff
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification; // Tambahan facade notifikasi

class BorrowingController extends Controller
{
    /**
     * Konstanta status, biar konsisten di semua tempat (controller & blade).
     * Ganti value di sini kalau mau ubah penamaan, gak perlu ganti satu-satu.
     */
    private const STATUS_PENDING  = 'pending';
    private const STATUS_BORROWED = 'borrowed';
    private const STATUS_REJECTED = 'rejected';
    private const STATUS_RETURNED = 'returned';

    public function index()
    {
        $borrowings = Borrowing::with(['user', 'details.product'])->latest()->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'details.product']);
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Export seluruh data borrowing ke file CSV (dibuka Excel).
     * Pakai fputcsv bawaan PHP, gak butuh package tambahan.
     */
    public function exportExcel()
    {
        $borrowings = Borrowing::with(['user', 'details.product'])->latest()->get();
        $filename = "data_borrowings.csv";
        $handle = fopen('php://temp', 'r+');

        // 1. Header kolom
        fputcsv($handle, ['No', 'Borrower', 'Email', 'Product(s)', 'Total Qty', 'Borrow Date', 'Due Date', 'Return Date', 'Status', 'Purpose']);

        // 2. Isi data
        $no = 1;
        foreach ($borrowings as $borrowing) {
            $products = $borrowing->details->map(function ($detail) {
                return ($detail->product->name ?? 'Unknown') . ' (x' . $detail->qty . ')';
            })->implode(', ');

            fputcsv($handle, [
                $no++,
                $borrowing->user->name ?? 'Unknown User',
                $borrowing->user->email ?? '-',
                $products,
                $borrowing->details->sum('qty'),
                $borrowing->borrow_date ? \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') : '-',
                $borrowing->due_date ? \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') : '-',
                $borrowing->return_date ? \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') : '-',
                ucfirst($borrowing->status),
                $borrowing->purpose ?? '-',
            ]);
        }

        rewind($handle);
        $csvData = stream_get_contents($handle);
        fclose($handle);

        // 3. Kirim file untuk di-download
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export seluruh data borrowing ke file PDF
     */
    public function exportPdf()
    {
        $borrowings = Borrowing::with(['user', 'details.product'])->latest()->get();

        $pdf = Pdf::loadView('borrowings.export-pdf', compact('borrowings'))->setPaper('a4', 'landscape');

        return $pdf->download('borrowings-' . now()->format('Y-m-d') . '.pdf');
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('borrowings.create', compact('products'));
    }

    /**
     * STAFF: Mengajukan permintaan peminjaman.
     * Status awal = Pending. Stok BELUM dikurangi di sini.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'qty'         => 'required|integer|min:1',
            'borrow_date' => 'required|date',
            'due_date'    => 'required|date|after_or_equal:borrow_date',
            'purpose'     => 'nullable|string|max:255', // Keperluan (opsional)
        ]);

        $product = Product::findOrFail($request->product_id);

        // Validasi stok tetap dicek di awal supaya user gak ngajuin request
        // yang jelas-jelas gak mungkin di-approve, tapi stok BELUM dipotong.
        if ($product->stock < $request->qty) {
            return back()->withErrors(['qty' => 'Stok barang tidak mencukupi.'])->withInput();
        }

        DB::transaction(function () use ($request, $product) {
            $borrowing = Borrowing::create([
                'user_id'     => Auth::id(),
                'borrow_date' => $request->borrow_date,
                'due_date'    => $request->due_date,
                'purpose'     => $request->purpose,
                'status'      => self::STATUS_PENDING,
            ]);

            BorrowingDetail::create([
                'borrowing_id' => $borrowing->id,
                'product_id'   => $product->id,
                'qty'          => $request->qty,
                'item_status'  => 'Menunggu Approval', // placeholder, diupdate jadi kondisi asli saat approve
            ]);
        });

        // ===============================================================
        // KIRIM NOTIFIKASI KE ADMIN (Ada Peminjaman Baru)
        // ===============================================================
        try {
            $pesan = "Permintaan peminjaman baru diajukan oleh " . Auth::user()->name;
            $url = route('borrowings.index'); 

            $admins = User::whereHas('role', function ($query) {
                $query->where('name', 'Admin');
            })->get();

            if ($admins->count() > 0) {
                Notification::send($admins, new NewPeminjamanNotification($pesan, $url));
            }
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi peminjaman: ' . $e->getMessage());
        }
        // ===============================================================

        return redirect()->route('borrowings.index')->with('success', 'Permintaan peminjaman berhasil diajukan, menunggu persetujuan admin.');
    }

    /**
     * ADMIN: Menyetujui permintaan peminjaman.
     * Di sinilah stok baru dikurangi (bukan waktu staff submit).
     */
    public function approve(Borrowing $borrowing)
    {
        $this->authorizeAdmin();

        if ($borrowing->status !== self::STATUS_PENDING) {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        // Cek ulang stok saat approve (bisa aja stok berubah sejak staff submit)
        foreach ($borrowing->details as $detail) {
            $product = Product::find($detail->product_id);
            if (!$product || $product->stock < $detail->qty) {
                return back()->with('error', "Stok untuk {$detail->product->name} tidak lagi mencukupi.");
            }
        }

        DB::transaction(function () use ($borrowing) {
            foreach ($borrowing->details as $detail) {
                $product = Product::find($detail->product_id);
                $product->decrement('stock', $detail->qty);
                $detail->update(['item_status' => 'Baik']);
            }

            $borrowing->update(['status' => self::STATUS_BORROWED]);
        });

        // ===============================================================
        // KIRIM NOTIFIKASI KE STAFF (APPROVE)
        // ===============================================================
        if ($borrowing->user) {
            $pesan = "Hore! Permintaan peminjaman Anda telah di-APPROVE oleh Admin.";
            $url = route('borrowings.index');
            $borrowing->user->notify(new BorrowingStatusUpdatedNotification($pesan, $url));
        }
        // ===============================================================

        return redirect()->route('borrowings.index')->with('success', 'Peminjaman disetujui. Stok telah dikurangi.');
    }

    /**
     * ADMIN: Menolak permintaan peminjaman.
     * Stok tidak berubah karena memang belum pernah dikurangi.
     */
    public function reject(Request $request, Borrowing $borrowing)
    {
        $this->authorizeAdmin();

        if ($borrowing->status !== self::STATUS_PENDING) {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $borrowing->update([
            'status'           => self::STATUS_REJECTED,
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        // ===============================================================
        // KIRIM NOTIFIKASI KE STAFF (REJECT)
        // ===============================================================
        if ($borrowing->user) {
            $pesan = "Maaf, permintaan peminjaman Anda di-REJECT oleh Admin.";
            $url = route('borrowings.index');
            $borrowing->user->notify(new BorrowingStatusUpdatedNotification($pesan, $url));
        }
        // ===============================================================

        return redirect()->route('borrowings.index')->with('success', 'Permintaan peminjaman ditolak.');
    }

    /**
     * ADMIN: Memproses pengembalian barang.
     * Stok dikembalikan (+qty) sesuai kondisi barang yang dilaporkan.
     * Setiap item punya kondisi & foto masing-masing (karena satu peminjaman
     * bisa berisi beberapa produk sekaligus).
     */
    public function returnItem(Request $request, Borrowing $borrowing)
    {
        $this->authorizeAdmin();

        if ($borrowing->status !== self::STATUS_BORROWED) {
            return back()->with('error', 'Peminjaman ini belum dalam status dipinjam / sudah selesai.');
        }

        $request->validate([
            'return_date'          => 'required|date|after_or_equal:borrow_date',
            'item_status'          => 'required|array',
            'item_status.*'        => 'required|string',
            'photo'                => 'nullable|array',
            'photo.*'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // maks 2MB
        ]);

        DB::transaction(function () use ($request, $borrowing) {
            $borrowing->update([
                'return_date' => $request->return_date,
                'status'      => self::STATUS_RETURNED,
            ]);

            foreach ($borrowing->details as $detail) {
                $data = [
                    'item_status' => $request->input("item_status.{$detail->id}"),
                ];

                // Simpan foto kalau ada yang di-upload untuk item ini
                if ($request->hasFile("photo.{$detail->id}")) {
                    $path = $request->file("photo.{$detail->id}")->store('borrowing-photos', 'public');
                    $data['photo'] = $path;
                }

                $detail->update($data);

                $product = Product::find($detail->product_id);
                $product->increment('stock', $detail->qty);
            }
        });

        // ===============================================================
        // KIRIM NOTIFIKASI KE STAFF (RETURN)
        // ===============================================================
        if ($borrowing->user) {
            $pesan = "Barang pinjaman Anda telah berhasil DIKEMBALIKAN dan diproses Admin.";
            $url = route('borrowings.index');
            $borrowing->user->notify(new BorrowingStatusUpdatedNotification($pesan, $url));
        }
        // ===============================================================

        return redirect()->route('borrowings.index')->with('success', 'Barang berhasil dikembalikan.');
    }

    public function edit(Borrowing $borrowing)
    {
        $products = Product::where('stock', '>', 0)
            ->orWhereIn('id', $borrowing->details->pluck('product_id'))
            ->get();

        $borrowing->load('details.product');

        return view('borrowings.edit', compact('borrowing', 'products'));
    }

    /**
     * Helper sederhana: pastikan yang approve/reject/return cuma Admin.
     * Sesuaikan nama role ('Admin') dengan isi tabel roles kamu.
     */
    private function authorizeAdmin(): void
    {
        if (Auth::user()->role?->name !== 'Admin') {
            abort(403, 'Hanya Admin yang boleh melakukan aksi ini.');
        }
    }
}