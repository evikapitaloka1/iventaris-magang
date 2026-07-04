<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Mengambil semua data produk dari database beserta kategorinya
    */
    public function collection()
    {
        return Product::with('category')->get();
    }

    /**
    * Menentukan judul kolom (Header) di baris pertama Excel
    */
    public function headings(): array
    {
        return [
            'No',
            'Kode Produk',
            'Nama Produk',
            'Kategori',
            'Stok',
            'Lokasi',
            'Kondisi',
        ];
    }

    /**
    * Memetakan data dari database ke dalam urutan kolom Excel
    */
    public function map($product): array
    {
        // Membuat nomor urut statis berdasarkan ID atau bisa juga dibiarkan menggunakan ID
        static $urutan = 0;
        $urutan++;

        return [
            $urutan,
            $product->product_code,
            $product->name,
            $product->category ? $product->category->name : 'Uncategorized',
            $product->stock,
            $product->location,
            $product->condition,
        ];
    }
}