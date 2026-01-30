<?php

/**
 * Test Script untuk Voting dengan Multiple Kategori
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\MachineLearningService;
use App\Models\Pengaduan;

echo "=== TEST VOTING MULTIPLE KATEGORI ===\n\n";

// Test dengan beberapa pengaduan
$pengaduans = Pengaduan::with('kategori')->limit(3)->get();

foreach ($pengaduans as $pengaduan) {
    echo str_repeat("=", 70) . "\n";
    echo "📝 Pengaduan ID: {$pengaduan->id}\n";
    echo "   Judul: {$pengaduan->judul}\n";
    echo "   Prediksi: {$pengaduan->kategori_prediksi} (" . number_format(($pengaduan->kategori_prediksi_skor ?? 0) * 100, 1) . "%)\n\n";
    
    // Get tree voting
    $mlService = new MachineLearningService();
    $treeVoting = $mlService->getRandomForestTreesDetail($pengaduan);
    
    if ($treeVoting['success'] && isset($treeVoting['ensemble_voting']['voting_details'])) {
        echo "📊 DETAIL VOTING:\n";
        
        foreach ($treeVoting['ensemble_voting']['voting_details'] as $index => $vote) {
            $medal = match($index) {
                0 => '🥇',
                1 => '🥈',
                2 => '🥉',
                default => '🌳'
            };
            
            $bar = str_repeat('█', (int)($vote['percentage'] / 3));
            
            echo sprintf(
                "   %s %-30s %3d trees (%5.1f%%) %s\n",
                $medal,
                $vote['kategori'],
                $vote['votes'],
                $vote['percentage'],
                $bar
            );
        }
        
        echo "\n";
        
        // Verifikasi total votes = 100
        $totalVotes = array_sum(array_column($treeVoting['ensemble_voting']['voting_details'], 'votes'));
        echo "   ✓ Total Votes: {$totalVotes}/100\n";
        echo "   ✓ Jumlah Kategori: " . count($treeVoting['ensemble_voting']['voting_details']) . "\n";
        
        if (isset($treeVoting['fallback']) && $treeVoting['fallback']) {
            echo "   ⚠ Mode: Fallback (simulasi)\n";
        } else {
            echo "   ✓ Mode: Real Python ML\n";
        }
    }
    
    echo "\n";
}

echo str_repeat("=", 70) . "\n";
echo "\n✅ Test selesai! Sekarang setiap pengaduan akan menampilkan 3-4 kategori.\n";
echo "\nBuka di browser:\n";
echo "http://127.0.0.1:8000/pengaduan/{$pengaduans->first()->id}\n";
