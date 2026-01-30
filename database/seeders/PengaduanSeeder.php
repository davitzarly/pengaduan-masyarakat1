<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengaduan;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Str;

class PengaduanSeeder extends Seeder
{
    public function run()
    {
        $kategori = Kategori::first();
        $user = User::first();

        if (! $kategori) {
            $kategori = Kategori::create(['nama' => 'Umum', 'deskripsi' => 'Kategori umum']);
        }

        if (! $user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'role' => User::ROLE_ADMIN,
            ]);
        }

        // Create sample pengaduan
        for ($i = 1; $i <= 8; $i++) {
            Pengaduan::create([
                'judul' => 'Contoh Pengaduan #' . $i,
                'deskripsi' => Str::random(120),
                'kategori_id' => $kategori->id,
                'user_id' => $user->id,
                'status' => ['pending', 'diproses', 'selesai'][array_rand([0,1,2])],
            ]);
        }
    }
}
