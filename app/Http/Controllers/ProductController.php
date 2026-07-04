<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ProductExport;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Pencarian menggunakan ilike khusus untuk PostgreSQL
        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'ilike', "%{$search}%")
                             ->orWhere('product_code', 'ilike', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
{
    // 1. Validasi input
    $validatedData = $request->validate([
        'category_id'  => 'required',
        'product_code' => 'required|unique:products',
        'name'         => 'required',
        'stock'        => 'required|integer',
        'location'     => 'required',
        'condition'    => 'required',
        'image_path'   => 'nullable|image|file|max:2048', // Pastikan validasi ini ada
    ]);

    // 2. Cek apakah ada file yang diupload
    if ($request->hasFile('image_path')) {
        // Simpan gambar ke folder storage/app/public/product-images
        $validatedData['image_path'] = $request->file('image_path')->store('product-images', 'public');
    }

    // 3. Simpan ke database
    Product::create($validatedData);

    return redirect()->route('products.index')->with('success', 'Product added successfully!');
}

    public function show(Product $product)
    {
        $product->load('category');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'product_code' => 'required|max:50|unique:products,product_code,'.$product->id,
        'name' => 'required|string|max:255',
        'stock' => 'required|integer|min:0',
        'location' => 'required|string|max:255',
        'condition' => 'required|string|max:50',
        'image_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // PERBAIKAN: Ganti 'image' jadi 'image_path'
    ]);

    if ($request->hasFile('image_path')) { // PERBAIKAN: Ganti 'image' jadi 'image_path'
        // Hapus gambar lama jika ada
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        // Simpan ke 'product-images' agar seragam dengan method store
        $validated['image_path'] = $request->file('image_path')->store('product-images', 'public'); 
    }

    $product->update($validated);

    return redirect()->route('products.index')->with('success', 'Data barang berhasil diperbarui.');
}
    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Barang berhasil dihapus.');
    }
   public function exportExcel()
    {
        $products = Product::with('category')->get();
        $filename = "data_products.csv";
        $handle = fopen('php://temp', 'r+'); // Membuka memori sementara
        
        // 1. Membuat Header Kolom
        fputcsv($handle, ['No', 'Kode Produk', 'Nama Produk', 'Kategori', 'Stok', 'Lokasi', 'Kondisi']);

        // 2. Memasukkan Data dari Database
        $no = 1;
        foreach($products as $product) {
            fputcsv($handle, [
                $no++,
                $product->product_code,
                $product->name,
                $product->category ? $product->category->name : 'Uncategorized',
                $product->stock,
                $product->location,
                $product->condition
            ]);
        }

        rewind($handle);
        $csvData = stream_get_contents($handle);
        fclose($handle);

        // 3. Mengirim File untuk Di-download
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function exportPdf()
    {
        // Mengambil data produk beserta relasi kategori
        $products = Product::with('category')->get();

        // Me-load view 'products.pdf' yang sudah kita buat tadi
        $pdf = Pdf::loadView('products.pdf', compact('products'));
        
        // Mengunduh file PDF dengan nama 'data_products.pdf'
        return $pdf->download('data_products.pdf');
    }
}