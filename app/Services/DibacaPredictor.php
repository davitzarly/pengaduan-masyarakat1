<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DibacaPredictor
{
    public function predict(string $subjek, string $pesan): ?array
    {
        $apiUrl = trim((string) config('ml.api_url'));
        if ($apiUrl !== '') {
            $apiResult = $this->predictViaApi($apiUrl, $subjek, $pesan);
            if (is_array($apiResult)) {
                return $apiResult;
            }
        }

        return $this->predictViaLocal($subjek, $pesan);
    }

    private function predictViaApi(string $apiUrl, string $subjek, string $pesan): ?array
    {
        $start = microtime(true);
        try {
            $response = Http::timeout(3)->post(rtrim($apiUrl, '/') . '/predict/dibaca', [
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

        $timeMs = (int) round((microtime(true) - $start) * 1000);

        return [
            'label' => $data['label'] ?? null,
            'label_text' => $data['label_text'] ?? null,
            'score' => $data['score'] ?? null,
            'source' => 'api',
            'time_ms' => $timeMs,
        ];
    }

    private function predictViaLocal(string $subjek, string $pesan): ?array
    {
        $python = config('ml.python');
        $script = config('ml.script');
        $modelDir = config('ml.model_dir');

        if (!$python || !$script || !file_exists($script)) {
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

        return [
            'label' => $result['label'] ?? null,
            'label_text' => $result['label_text'] ?? null,
            'score' => $result['score'] ?? null,
            'source' => 'local',
            'time_ms' => $timeMs,
            'raw' => [
                'stdout' => $stdout,
                'stderr' => $stderr,
            ],
        ];
    }
}
