<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'category_id' => 1, 
                'product_code' => 'NET-001', 
                'name' => 'Cisco Router 2960', 
                'stock' => 5, 
                'location' => 'Ruang Server L2', 
                'condition' => 'Baik',
                'image_path' => 'images/router.jpg', // Path ke router.jpg
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 2, 
                'product_code' => 'COM-015', 
                'name' => 'Lenovo ThinkPad T14', 
                'stock' => 20, 
                'location' => 'Gudang IT', 
                'condition' => 'Baik',
                'image_path' => 'images/lenovo.jpg', // Path ke lenovo.jpg
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'category_id' => 3, 
                'product_code' => 'ACC-055', 
                'name' => 'Kabel UTP Cat6 50m', 
                'stock' => 15, 
                'location' => 'Lemari A', 
                'condition' => 'Baik',
                'image_path' => 'images/kabel.jpg', // Path ke kabel.jpg
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);
    }
}