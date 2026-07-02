<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            ['role_id' => 1, 'name' => 'Admin IT', 'email' => 'admin@test.com', 'password' => Hash::make('password123'), 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'name' => 'Staff Karyawan', 'email' => 'staff@test.com', 'password' => Hash::make('password123'), 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 3, 'name' => 'Manager Operasional', 'email' => 'manager@test.com', 'password' => Hash::make('password123'), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}