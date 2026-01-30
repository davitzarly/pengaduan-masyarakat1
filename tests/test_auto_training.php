<?php

/**
 * Test Script untuk Auto-Training ML
 * Mensimulasikan pengaduan baru dan trigger auto-training
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\MachineLearningService;
use Illuminate\Support\Facades\Log;

echo "=== TEST AUTO-TRAINING ML ===\n\n";

// Simulasi data pengaduan
$testData = [
    'id' => 999,
    'judul' => 'Biaya Layanan',
    'deskripsi' => 'Berapa biaya untuk pembuatan KTP baru? Apakah gratis atau ada tarif tertentu?',
    'kategori' => (object)[
        'nama' => 'Biaya Layanan'
    ]
];

$pelapor = [
    'nama' => 'Test User',
    'email' => 'test@example.com'
];

echo "📝 Data Pengaduan Test:\n";
echo "   ID: {$testData['id']}\n";
echo "   Judul: {$testData['judul']}\n";
echo "   Kategori: {$testData['kategori']->nama}\n";
echo "   Deskripsi: " . substr($testData['deskripsi'], 0, 50) . "...\n\n";

// Trigger auto-training
echo "🤖 Memulai Auto-Training...\n\n";

try {
    $mlService = new MachineLearningService();
    $result = $mlService->autoTrainWithNewData((object)$testData, $pelapor);
    
    if ($result['success']) {
        echo "✅ SUCCESS: {$result['message']}\n";
    } else {
        echo "❌ FAILED: {$result['message']}\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: {$e->getMessage()}\n";
    echo "\nStack Trace:\n{$e->getTraceAsString()}\n";
}

echo "\n=== TEST SELESAI ===\n";
echo "\nCek log untuk detail:\n";
echo "- Laravel: storage/logs/laravel.log\n";
echo "- Python: ALGORITMA RANDOM FOREST/auto_training_log.txt\n";
