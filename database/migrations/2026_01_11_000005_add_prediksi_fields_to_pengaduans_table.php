<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->string('kategori_prediksi')->nullable()->after('kategori_id');
            $table->decimal('kategori_prediksi_skor', 5, 4)->nullable()->after('kategori_prediksi');
            $table->string('kategori_prediksi_sumber', 20)->nullable()->after('kategori_prediksi_skor');
            $table->unsignedInteger('prediksi_kategori_ms')->nullable()->after('kategori_prediksi_sumber');
            $table->string('prediksi_dibaca_sumber', 20)->nullable()->after('prediksi_skor');
            $table->unsignedInteger('prediksi_dibaca_ms')->nullable()->after('prediksi_dibaca_sumber');
            $table->string('dibaca_verifikasi', 1)->nullable()->after('prediksi_dibaca_ms');
            $table->string('nik', 32)->nullable()->after('dibaca_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn([
                'kategori_prediksi',
                'kategori_prediksi_skor',
                'kategori_prediksi_sumber',
                'prediksi_kategori_ms',
                'prediksi_dibaca_sumber',
                'prediksi_dibaca_ms',
                'dibaca_verifikasi',
                'nik',
            ]);
        });
    }
};
