<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class);
    }

    public static function formOptions()
    {
        $ordered = config('kategori.form_options', []);
        if (!is_array($ordered) || $ordered === []) {
            return self::orderBy('nama')->get();
        }

        $descriptions = config('kategori.descriptions', []);
        foreach ($ordered as $name) {
            self::firstOrCreate(
                ['nama' => $name],
                ['deskripsi' => $descriptions[$name] ?? null]
            );
        }

        $kategoris = self::whereIn('nama', $ordered)->get();
        return $kategoris
            ->sortBy(fn ($kategori) => array_search($kategori->nama, $ordered, true))
            ->values();
    }
}
