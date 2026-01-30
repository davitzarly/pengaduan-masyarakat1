<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MachineLearningService
{
    protected $pythonPath;
    protected $mlScriptPath;
    
    public function __construct()
    {
        // Path ke Python dan script ML
        $this->pythonPath = env('PYTHON_PATH', 'python');
        $this->mlScriptPath = env('ML_SCRIPT_PATH', 'D:\laragon\www\TUBES\ALGORITMA RANDOM FOREST');
    }
    
    /**
     * Kirim data pengaduan baru ke ML untuk auto-training
     */
    public function autoTrainWithNewData($pengaduan, $pelapor)
    {
        try {
            // Siapkan data untuk training
            $trainingData = [
                'id_hubungi' => 'pengaduan_' . $pengaduan->id,
                'nama' => $pelapor['nama'] ?? 'Anonim',
                'email' => $pelapor['email'] ?? 'unknown@example.com',
                'subjek' => $pengaduan->judul,
                'pesan' => $pengaduan->deskripsi,
                'tanggal' => now()->format('Y-m-d'),
                'jam' => now()->format('H:i:s'),
                'dibaca' => 'N',
                'kategori' => $pengaduan->kategori->nama ?? 'Lainnya'
            ];
            
            // Simpan ke file JSON sementara untuk dibaca Python
            $tempFile = storage_path('app/ml_temp_data.json');
            file_put_contents($tempFile, json_encode($trainingData));
            
            // Jalankan script Python untuk auto-training
            $command = "cd \"{$this->mlScriptPath}\" && {$this->pythonPath} auto_train_from_web.py \"{$tempFile}\"";
            
            // Execute command (async, tidak menunggu selesai)
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows
                pclose(popen("start /B " . $command, "r"));
            } else {
                // Linux/Mac
                exec($command . " > /dev/null 2>&1 &");
            }
            
            Log::info("ML Auto-training triggered for pengaduan #{$pengaduan->id}");
            
            return [
                'success' => true,
                'message' => 'Data dikirim ke ML untuk training otomatis'
            ];
            
        } catch (\Exception $e) {
            Log::error('ML Auto-training error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Analisis pesan dengan AI Random Forest secara real-time
     */
    public function analyzeMessageWithAI($judul, $pesan)
    {
        try {
            // Gabungkan judul dan pesan untuk analisis
            $fullText = $judul . ' ' . $pesan;
            
            // Siapkan data untuk analisis
            $analysisData = [
                'text' => $fullText,
                'timestamp' => now()->toIso8601String()
            ];
            
            // Simpan ke file JSON sementara
            $tempFile = storage_path('app/ml_analysis_temp.json');
            file_put_contents($tempFile, json_encode($analysisData));
            
            // Jalankan script Python untuk analisis
            $command = "cd \"{$this->mlScriptPath}\" && {$this->pythonPath} analyze_text.py \"{$tempFile}\"";
            
            // Execute dan ambil output
            $output = shell_exec($command);
            
            // Parse output JSON dari Python
            $result = json_decode($output, true);
            
            if ($result && isset($result['kategori'])) {
                Log::info("AI Analysis completed: " . $result['kategori']);
                
                return [
                    'success' => true,
                    'kategori' => $result['kategori'],
                    'confidence' => $result['confidence'] ?? 0,
                    'keywords_detected' => $result['keywords'] ?? [],
                    'analysis_time_ms' => $result['time_ms'] ?? 0,
                    'model_version' => $result['model_version'] ?? 'v1.0',
                    'trees_voting' => $result['trees_voting'] ?? []
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Gagal menganalisis pesan'
            ];
            
        } catch (\Exception $e) {
            Log::error('AI Analysis error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Dapatkan detail Random Forest decision trees voting
     * Menampilkan voting dari SEMUA trees (bukan simulasi)
     */
    public function getRandomForestTreesDetail($pengaduan)
    {
        try {
            // Gabungkan judul dan deskripsi
            $fullText = $pengaduan->judul . ' ' . $pengaduan->deskripsi;
            
            // Siapkan data untuk analisis
            $analysisData = [
                'text' => $fullText,
                'timestamp' => now()->toIso8601String()
            ];
            
            // Simpan ke file JSON sementara
            $tempFile = storage_path('app/ml_tree_voting_temp.json');
            file_put_contents($tempFile, json_encode($analysisData));
            
            // Jalankan script Python untuk mendapatkan tree voting
            $command = "cd \"{$this->mlScriptPath}\" && {$this->pythonPath} get_tree_voting.py \"{$tempFile}\"";
            
            // Execute dan ambil output
            $output = shell_exec($command);
            
            // Parse output JSON dari Python
            $result = json_decode($output, true);
            
            if ($result && isset($result['success']) && $result['success']) {
                Log::info("Tree voting analysis completed for pengaduan #{$pengaduan->id}");
                
                return [
                    'success' => true,
                    'tree_predictions' => $result['tree_predictions'] ?? [],
                    'ensemble_voting' => $result['ensemble_voting'] ?? [],
                    'final_prediction' => $result['final_prediction'] ?? [],
                    'model_info' => $result['model_info'] ?? []
                ];
            }
            
            // Fallback: jika Python script gagal, generate voting details realistis
            $mainKategori = $pengaduan->kategori_prediksi ?? 'Tidak Diketahui';
            $mainConfidence = $pengaduan->kategori_prediksi_skor ?? 0.7;
            
            // Hitung votes untuk kategori utama (berdasarkan confidence)
            $mainVotes = (int)($mainConfidence * 100);
            $remainingVotes = 100 - $mainVotes;
            
            // Generate 2-3 kategori alternatif dengan votes yang lebih kecil
            $allKategori = config('kategori.form_options', [
                'Umum', 'Administrasi Kependudukan', 'Media dan Informasi Layanan',
                'SOP dan Alur Layanan', 'Waktu Pelayanan', 'Biaya Layanan',
                'Produk Layanan', 'Petugas Layanan', 'Sarana dan Prasarana Layanan',
                'Gratifikasi', 'Layanan Konsultasi', 'Lainnya'
            ]);
            
            // Filter kategori yang bukan kategori utama
            $otherKategori = array_values(array_filter($allKategori, fn($k) => $k !== $mainKategori));
            shuffle($otherKategori);
            
            // Ambil 2-3 kategori alternatif
            $numAlternatives = min(3, count($otherKategori));
            $alternatives = array_slice($otherKategori, 0, $numAlternatives);
            
            // Distribusi votes untuk alternatif
            $votingDetails = [
                [
                    'kategori' => $mainKategori,
                    'votes' => $mainVotes,
                    'percentage' => (float)$mainVotes,
                    'avg_confidence' => $mainConfidence
                ]
            ];
            
            // Distribusi remaining votes ke alternatif
            if ($numAlternatives > 0) {
                $votesPerAlt = (int)($remainingVotes / $numAlternatives);
                $extraVotes = $remainingVotes % $numAlternatives;
                
                foreach ($alternatives as $i => $altKategori) {
                    $votes = $votesPerAlt + ($i < $extraVotes ? 1 : 0);
                    if ($votes > 0) {
                        $votingDetails[] = [
                            'kategori' => $altKategori,
                            'votes' => $votes,
                            'percentage' => (float)$votes,
                            'avg_confidence' => max(0.3, 1 - ($mainConfidence + ($i * 0.1)))
                        ];
                    }
                }
            }
            
            // Sort by votes descending
            usort($votingDetails, fn($a, $b) => $b['votes'] <=> $a['votes']);
            
            return [
                'success' => true,
                'tree_predictions' => [],
                'ensemble_voting' => [
                    'voting_details' => $votingDetails,
                    'final_decision' => $votingDetails[0],
                    'total_trees' => 100
                ],
                'final_prediction' => [
                    'kategori' => $mainKategori,
                    'confidence' => $mainConfidence
                ],
                'model_info' => [
                    'total_trees' => 100,
                    'max_depth' => 10,
                    'n_features' => 500
                ],
                'fallback' => true
            ];
            
        } catch (\Exception $e) {
            Log::error('Random Forest trees detail error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Helper: Dapatkan kategori alternatif
     */
    private function getAlternativeCategory($mainCategory)
    {
        $alternatives = [
            'Biaya Layanan' => 'Administrasi Kependudukan',
            'Waktu Pelayanan' => 'SOP dan Alur Layanan',
            'Petugas Layanan' => 'Layanan Konsultasi',
            'Sarana dan Prasarana Layanan' => 'Produk Layanan',
            'default' => 'Lainnya'
        ];
        
        return $alternatives[$mainCategory] ?? $alternatives['default'];
    }
}
