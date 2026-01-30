<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $names = config('kategori.form_options', []);
        if (!is_array($names) || $names === []) {
            $names = ['Umum'];
        }

        $descriptions = config('kategori.descriptions', []);

        foreach ($names as $name) {
            Kategori::firstOrCreate(
                ['nama' => $name],
                ['deskripsi' => $descriptions[$name] ?? null]
            );
        }
    }
}
