<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus user lama jika ada (agar tidak duplikat)
        User::truncate();

        // Buat akun admin default
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@produk.com',
            'password' => Hash::make('admin123'),
        ]);

        $this->command->info('✅ Akun admin berhasil dibuat!');
        $this->command->info('   Email    : admin@produk.com');
        $this->command->info('   Password : admin123');
    }
}