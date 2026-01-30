<?php

/**
 * Test Script untuk Tree Voting Analysis
 * Mensimulasikan analisis voting dari decision trees
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\MachineLearningService;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\Log;

echo "=== TEST TREE VOTING ANALYSIS ===\n\n";

// Ambil pengaduan pertama untuk test
$pengaduan = Pengaduan::with('kategori')->first();

if (!$pengaduan) {
    echo "❌ Tidak ada pengaduan untuk ditest\n";
    exit(1);
}

echo "📝 Data Pengaduan:\n";
echo "   ID: {$pengaduan->id}\n";
echo "   Judul: {$pengaduan->judul}\n";
echo "   Kategori: " . ($pengaduan->kategori->nama ?? 'N/A') . "\n";
echo "   Prediksi AI: {$pengaduan->kategori_prediksi}\n";
echo "   Confidence: " . number_format(($pengaduan->kategori_prediksi_skor ?? 0) * 100, 1) . "%\n\n";

// Trigger tree voting analysis
echo "🌳 Memulai Tree Voting Analysis...\n\n";

try {
    $mlService = new MachineLearningService();
    $result = $mlService->getRandomForestTreesDetail($pengaduan);
    
    if ($result['success']) {
        echo "✅ SUCCESS: Tree voting analysis completed!\n\n";
        
        // Tampilkan ensemble voting
        if (isset($result['ensemble_voting']['voting_details'])) {
            echo "📊 ENSEMBLE VOTING:\n";
            foreach ($result['ensemble_voting']['voting_details'] as $vote) {
                $bar = str_repeat('█', (int)($vote['percentage'] / 2));
                echo sprintf(
                    "   %s: %d votes (%.1f%%) %s\n",
                    str_pad($vote['kategori'], 30),
                    $vote['votes'],
                    $vote['percentage'],
                    $bar
                );
            }
            echo "\n";
        }
        
        // Tampilkan final decision
        if (isset($result['ensemble_voting']['final_decision'])) {
            $final = $result['ensemble_voting']['final_decision'];
            echo "� FINAL DECISION:\n";
            echo "   Kategori: {$final['kategori']}\n";
            echo "   Votes: {$final['votes']} / {$result['ensemble_voting']['total_trees']}\n";
            echo "   Percentage: " . number_format($final['percentage'], 1) . "%\n\n";
        }
        
        // Tampilkan model info
        if (isset($result['model_info'])) {
            $info = $result['model_info'];
            echo "🤖 MODEL INFO:\n";
            echo "   Total Trees: {$info['total_trees']}\n";
            echo "   Max Depth: {$info['max_depth']}\n";
            echo "   Features: {$info['n_features']}\n\n";
        }
        
        // Tampilkan sample tree predictions
        if (isset($result['tree_predictions']) && count($result['tree_predictions']) > 0) {
            echo "🌲 SAMPLE TREE PREDICTIONS (first 5):\n";
            $sample = array_slice($result['tree_predictions'], 0, 5);
            foreach ($sample as $tree) {
                echo sprintf(
                    "   Tree #%d: %s (%.1f%% confidence, depth: %d)\n",
                    $tree['tree_id'],
                    $tree['prediction'],
                    $tree['confidence'] * 100,
                    $tree['depth']
                );
            }
            echo "   ... dan " . (count($result['tree_predictions']) - 5) . " trees lainnya\n\n";
        }
        
        // Check if fallback mode
        if (isset($result['fallback']) && $result['fallback']) {
            echo "⚠️  WARNING: Using fallback mode (Python script not available)\n\n";
        }
        
    } else {
        echo "❌ FAILED: {$result['message']}\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: {$e->getMessage()}\n";
    echo "\nStack Trace:\n{$e->getTraceAsString()}\n";
}

echo "\n=== TEST SELESAI ===\n";
echo "\nUntuk melihat visualisasi lengkap, buka:\n";
echo "http://127.0.0.1:8000/pengaduan/{$pengaduan->id}/tree-voting\n";
