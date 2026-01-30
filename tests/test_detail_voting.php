<?php

/**
 * Test Script untuk Tree Voting di Halaman Detail
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\MachineLearningService;
use App\Models\Pengaduan;

echo "=== TEST TREE VOTING DI HALAMAN DETAIL ===\n\n";

// Ambil pengaduan pertama
$pengaduan = Pengaduan::with('kategori')->first();

if (!$pengaduan) {
    echo "❌ Tidak ada pengaduan\n";
    exit(1);
}

echo "📝 Pengaduan ID: {$pengaduan->id}\n";
echo "   Judul: {$pengaduan->judul}\n";
echo "   Kategori Aktual: " . ($pengaduan->kategori->nama ?? 'N/A') . "\n";
echo "   Prediksi AI: {$pengaduan->kategori_prediksi} (" . number_format(($pengaduan->kategori_prediksi_skor ?? 0) * 100, 1) . "%)\n\n";

// Get tree voting
$mlService = new MachineLearningService();
$treeVoting = $mlService->getRandomForestTreesDetail($pengaduan);

if ($treeVoting['success'] && isset($treeVoting['ensemble_voting']['voting_details'])) {
    echo "✅ Tree Voting Data Tersedia!\n\n";
    echo "📊 DETAIL VOTING PER KATEGORI:\n";
    echo str_repeat("=", 60) . "\n";
    
    foreach ($treeVoting['ensemble_voting']['voting_details'] as $index => $vote) {
        $medal = match($index) {
            0 => '🥇',
            1 => '🥈',
            2 => '🥉',
            default => '🌳'
        };
        
        $bar = str_repeat('█', (int)($vote['percentage'] / 2));
        
        echo sprintf(
            "%s #%d: %-30s %3d trees (%5.1f%%) %s\n",
            $medal,
            $index + 1,
            $vote['kategori'],
            $vote['votes'],
            $vote['percentage'],
            $bar
        );
    }
    
    echo str_repeat("=", 60) . "\n\n";
    
    echo "🎯 KEPUTUSAN FINAL:\n";
    $final = $treeVoting['ensemble_voting']['final_decision'];
    echo "   Kategori: {$final['kategori']}\n";
    echo "   Votes: {$final['votes']} / {$treeVoting['ensemble_voting']['total_trees']}\n";
    echo "   Percentage: " . number_format($final['percentage'], 1) . "%\n\n";
    
    echo "✅ Data ini akan ditampilkan di halaman detail pengaduan!\n";
    echo "   URL: http://127.0.0.1:8000/pengaduan/{$pengaduan->id}\n";
    
} else {
    echo "⚠️  Menggunakan fallback mode\n";
    echo "   (Python script tidak tersedia atau model belum ditraining)\n";
}

echo "\n=== TEST SELESAI ===\n";
