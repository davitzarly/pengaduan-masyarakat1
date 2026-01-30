<?php

/**
 * Test Script untuk Urutan Pengaduan
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pengaduan;

echo "=== TEST URUTAN PENGADUAN ===\n\n";

// Ambil pengaduan dengan urutan yang sama seperti di controller
$pengaduans = Pengaduan::with('kategori', 'user')->latest()->limit(10)->get();

echo "📋 Daftar Pengaduan (Terbaru ke Terlama):\n";
echo str_repeat("=", 80) . "\n";
echo sprintf("%-5s %-30s %-20s %-15s\n", "ID", "Judul", "Tanggal", "Status");
echo str_repeat("=", 80) . "\n";

foreach ($pengaduans as $pengaduan) {
    echo sprintf(
        "%-5s %-30s %-20s %-15s\n",
        $pengaduan->id,
        substr($pengaduan->judul, 0, 28),
        $pengaduan->created_at->format('d M Y, H:i'),
        $pengaduan->status
    );
}

echo str_repeat("=", 80) . "\n\n";

// Verifikasi urutan
$firstId = $pengaduans->first()->id;
$lastId = $pengaduans->last()->id;

echo "✓ Pengaduan Pertama (Terbaru): ID {$firstId} - {$pengaduans->first()->created_at->format('d M Y, H:i')}\n";
echo "✓ Pengaduan Terakhir (Terlama): ID {$lastId} - {$pengaduans->last()->created_at->format('d M Y, H:i')}\n\n";

if ($pengaduans->first()->created_at >= $pengaduans->last()->created_at) {
    echo "✅ URUTAN BENAR: Terbaru di atas (descending)\n";
} else {
    echo "❌ URUTAN SALAH: Terlama di atas (ascending)\n";
}

echo "\n=== TEST SELESAI ===\n";
