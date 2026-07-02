<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            ['name' => 'Perangkat Jaringan', 'description' => 'Router, Switch, Access Point', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Komputer & Server', 'description' => 'Laptop, PC, Server', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aksesoris IT', 'description' => 'Keyboard, Mouse, Kabel', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}