<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiKey;
    protected $sender;

    public function __construct()
    {
        // Konfigurasi WhatsApp API (bisa menggunakan Fonnte, Wablas, atau API lainnya)
        $this->apiUrl = env('WHATSAPP_API_URL', 'https://api.fonnte.com/send');
        $this->apiKey = env('WHATSAPP_API_KEY', 'oWh2TTsi7KNdp1ghMAe9');
        $this->sender = env('WHATSAPP_SENDER', '');
    }

    /**
     * Kirim notifikasi WhatsApp untuk pengaduan baru
     */
    public function sendPengaduanNotification($pengaduan, $pelapor)
    {
        try {
            // Nomor tujuan (admin atau nomor yang ditentukan)
            $adminPhone = env('WHATSAPP_ADMIN_NUMBER', '6285719370901');
            
            if (empty($adminPhone) || empty($this->apiKey)) {
                Log::warning('WhatsApp not configured. Skipping notification.');
                return [
                    'success' => false,
                    'message' => 'WhatsApp API not configured'
                ];
            }

            // Format pesan
            $message = $this->formatPengaduanMessage($pengaduan, $pelapor);

            // Kirim ke admin
            $response = $this->sendMessage($adminPhone, $message);

            // Jika ada email pelapor dan nomor WA, kirim konfirmasi ke pelapor juga
            if (!empty($pelapor['phone'])) {
                $confirmMessage = $this->formatConfirmationMessage($pengaduan, $pelapor);
                $this->sendMessage($pelapor['phone'], $confirmMessage);
            }

            return $response;

        } catch (\Exception $e) {
            Log::error('WhatsApp send error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Kirim pesan WhatsApp
     */
    protected function sendMessage($phone, $message)
    {
        try {
            // Format nomor (hapus karakter non-numeric, tambahkan 62 jika perlu)
            $phone = $this->formatPhoneNumber($phone);

            // Kirim via API (contoh menggunakan Fonnte)
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey
            ])->post($this->apiUrl, [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62'
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp sent to {$phone}");
                return [
                    'success' => true,
                    'message' => 'WhatsApp notification sent',
                    'response' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to send WhatsApp',
                'response' => $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp API error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Format nomor telepon
     */
    protected function formatPhoneNumber($phone)
    {
        // Hapus karakter non-numeric
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Jika diawali 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Jika belum ada 62, tambahkan
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    /**
     * Format pesan pengaduan untuk admin
     */
    protected function formatPengaduanMessage($pengaduan, $pelapor)
    {
        $kategori = $pengaduan['kategori'] ?? 'Tidak Diketahui';
        $prediksiKategori = $pengaduan['kategori_prediksi'] ?? $kategori;
        $skorPrediksi = isset($pengaduan['kategori_prediksi_skor']) 
            ? round($pengaduan['kategori_prediksi_skor'] * 100, 1) 
            : 0;

        $message = "🔔 *PENGADUAN BARU MASUK*\n\n";
        $message .= "📋 *Kategori:* {$kategori}\n";
        $message .= "🤖 *AI Prediksi:* {$prediksiKategori} ({$skorPrediksi}%)\n\n";
        $message .= "👤 *Pelapor:* {$pelapor['nama']}\n";
        $message .= "📧 *Email:* {$pelapor['email']}\n\n";
        $message .= "📝 *Detail Pengaduan:*\n{$pengaduan['detail']}\n\n";
        $message .= "⏰ *Waktu:* " . now()->format('d/m/Y H:i') . "\n\n";
        $message .= "Silakan segera tindaklanjuti pengaduan ini melalui dashboard admin.";

        return $message;
    }

    /**
     * Format pesan konfirmasi untuk pelapor
     */
    protected function formatConfirmationMessage($pengaduan, $pelapor)
    {
        $message = "✅ *PENGADUAN TERKIRIM*\n\n";
        $message .= "Terima kasih *{$pelapor['nama']}*,\n\n";
        $message .= "Pengaduan Anda telah kami terima dan akan segera ditindaklanjuti.\n\n";
        $message .= "📋 *Kategori:* {$pengaduan['kategori']}\n";
        $message .= "⏰ *Waktu:* " . now()->format('d/m/Y H:i') . "\n\n";
        $message .= "Tim kami akan menghubungi Anda jika diperlukan informasi tambahan.\n\n";
        $message .= "_Sistem Pengaduan Masyarakat_";

        return $message;
    }
}
