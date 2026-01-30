<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'kategori_id',
        'user_id',
        'nama_pelapor',
        'email_pelapor',
        'status',
        'prediksi_dibaca',
        'prediksi_skor',
        'prediksi_dibaca_sumber',
        'prediksi_dibaca_ms',
        'dibaca_verifikasi',
        'kategori_prediksi',
        'kategori_prediksi_skor',
        'kategori_prediksi_sumber',
        'prediksi_kategori_ms',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
