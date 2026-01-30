<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KategoriPredictor
{
    public function predict(string $subjek, string $pesan): ?array
    {
        $apiUrl = trim((string) config('kategori.api_url'));
        if ($apiUrl !== '') {
            $apiResult = $this->predictViaApi($apiUrl, $subjek, $pesan);
            if (is_array($apiResult)) {
                return $apiResult;
            }
        }

        $mlResult = $this->predictViaMl($subjek, $pesan);
        if (is_array($mlResult) && !empty($mlResult['kategori'])) {
            return $mlResult;
        }

        return $this->predictViaRules($subjek . ' ' . $pesan);
    }

    private function predictViaApi(string $apiUrl, string $subjek, string $pesan): ?array
    {
        $start = microtime(true);
        try {
            $response = Http::timeout(3)->post(rtrim($apiUrl, '/') . '/predict/kategori', [
                'subjek' => $subjek,
                'pesan' => $pesan,
            ]);
        } catch (\Throwable $e) {
            return null;
        }

        if (!$response->ok()) {
            return null;
        }

        $data = $response->json();
        if (!is_array($data) || isset($data['error'])) {
            return null;
        }

        $kategori = $data['kategori'] ?? $data['label'] ?? null;
        if (!$kategori) {
            return null;
        }

        $timeMs = (int) round((microtime(true) - $start) * 1000);

        return [
            'kategori' => $kategori,
            'score' => $data['score'] ?? null,
            'source' => 'api',
            'time_ms' => $timeMs,
        ];
    }

    private function predictViaMl(string $subjek, string $pesan): ?array
    {
        $python = config('kategori.python');
        $script = config('kategori.script');
        $modelDir = config('kategori.model_dir');

        if (!$python || !$script || !$modelDir || !file_exists($script)) {
            return null;
        }

        $modelPath = $modelDir . DIRECTORY_SEPARATOR . 'kategori_random_forest.pkl';
        $tfidfPath = $modelDir . DIRECTORY_SEPARATOR . 'kategori_tfidf.pkl';
        if (!file_exists($modelPath) || !file_exists($tfidfPath)) {
            return null;
        }

        $start = microtime(true);
        $cmd = escapeshellarg($python)
            . ' ' . escapeshellarg($script)
            . ' --model-dir ' . escapeshellarg($modelDir);

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($cmd, $descriptors, $pipes);
        if (!is_resource($process)) {
            return null;
        }

        $payload = json_encode([
            'subjek' => $subjek,
            'pesan' => $pesan,
        ], JSON_UNESCAPED_UNICODE);

        fwrite($pipes[0], $payload ?: '{}');
        fclose($pipes[0]);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);
        if ($exitCode !== 0) {
            return null;
        }

        $result = json_decode($stdout, true);
        if (!is_array($result) || isset($result['error'])) {
            return null;
        }

        $timeMs = (int) round((microtime(true) - $start) * 1000);
        $kategori = $result['kategori'] ?? $result['label'] ?? null;
        if (!$kategori) {
            return null;
        }

        return [
            'kategori' => $kategori,
            'score' => $result['score'] ?? null,
            'source' => 'ml',
            'time_ms' => $timeMs,
            'raw' => [
                'stdout' => $stdout,
                'stderr' => $stderr,
            ],
        ];
    }

    private function predictViaRules(string $text): ?array
    {
        $start = microtime(true);
        $rules = config('kategori.rules', []);
        if (!is_array($rules) || $rules === []) {
            return null;
        }

        $clean = $this->cleanText($text);
        $bestCategory = null;
        $bestScore = 0;
        $bestMatches = 0;

        foreach ($rules as $category => $keywords) {
            if (!is_array($keywords) || $keywords === []) {
                continue;
            }

            $matches = 0;
            foreach ($keywords as $keyword) {
                $keywordClean = $this->cleanText((string) $keyword);
                if ($keywordClean === '') {
                    continue;
                }

                if (str_contains($clean, $keywordClean)) {
                    $matches++;
                }
            }

            if ($matches > $bestScore) {
                $bestScore = $matches;
                $bestCategory = $category;
                $bestMatches = $matches;
            }
        }

        if ($bestCategory) {
            $totalKeywords = max(count($rules[$bestCategory]), 1);
            $timeMs = (int) round((microtime(true) - $start) * 1000);
            return [
                'kategori' => $bestCategory,
                'score' => $bestMatches / $totalKeywords,
                'source' => 'rules',
                'time_ms' => $timeMs,
            ];
        }

        $timeMs = (int) round((microtime(true) - $start) * 1000);
        return [
            'kategori' => config('kategori.default', 'Umum'),
            'score' => null,
            'source' => 'default',
            'time_ms' => $timeMs,
        ];
    }

    private function cleanText(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\\s]/', ' ', $text);
        $text = preg_replace('/\\s+/', ' ', $text);
        return trim($text);
    }
}
