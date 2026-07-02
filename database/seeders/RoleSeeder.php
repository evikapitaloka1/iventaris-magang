<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Staff', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}