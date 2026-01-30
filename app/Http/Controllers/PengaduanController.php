<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Kategori;
use App\Models\User;
use App\Services\DibacaPredictor;
use App\Services\KategoriPredictor;
use App\Services\WhatsAppService;
use App\Services\MachineLearningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduans = Pengaduan::with('kategori', 'user')->latest()->get();
        return view('pengaduan.index', compact('pengaduans'));
    }

    public function create()
    {
        return view('pengaduan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
        ]);

        $kategoriPrediksi = app(KategoriPredictor::class)
            ->predict($request->judul, $request->deskripsi);
        $kategoriId = $this->resolveKategoriId($kategoriPrediksi['kategori'] ?? null);

        $prediksi = app(DibacaPredictor::class)
            ->predict($request->judul, $request->deskripsi);

        Pengaduan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kategori_id' => $kategoriId,
            'user_id' => Auth::id(),
            'prediksi_dibaca' => $prediksi['label'] ?? null,
            'prediksi_skor' => $prediksi['score'] ?? null,
            'prediksi_dibaca_sumber' => $prediksi['source'] ?? null,
            'prediksi_dibaca_ms' => $prediksi['time_ms'] ?? null,
            'kategori_prediksi' => $kategoriPrediksi['kategori'] ?? null,
            'kategori_prediksi_skor' => $kategoriPrediksi['score'] ?? null,
            'kategori_prediksi_sumber' => $kategoriPrediksi['source'] ?? null,
            'prediksi_kategori_ms' => $kategoriPrediksi['time_ms'] ?? null,
        ]);

        return redirect()->route('pengaduan.index');
    }

    public function show(Pengaduan $pengaduan)
    {
        // Dapatkan detail tree voting untuk ditampilkan di halaman detail
        $mlService = app(MachineLearningService::class);
        $treeVoting = $mlService->getRandomForestTreesDetail($pengaduan);
        
        return view('pengaduan.show', compact('pengaduan', 'treeVoting'));
    }

    public function edit(Pengaduan $pengaduan)
    {
        return view('pengaduan.edit', compact('pengaduan'));
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
            'status' => 'required|in:pending,diproses,selesai',
            'dibaca_verifikasi' => 'nullable|in:Y,N',
        ]);

        $kategoriPrediksi = app(KategoriPredictor::class)
            ->predict($request->judul, $request->deskripsi);
        $kategoriId = $this->resolveKategoriId($kategoriPrediksi['kategori'] ?? null);

        $prediksi = app(DibacaPredictor::class)
            ->predict($request->judul, $request->deskripsi);

        $pengaduan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kategori_id' => $kategoriId,
            'status' => $request->status,
            'dibaca_verifikasi' => $request->dibaca_verifikasi,
            'prediksi_dibaca' => $prediksi['label'] ?? null,
            'prediksi_skor' => $prediksi['score'] ?? null,
            'prediksi_dibaca_sumber' => $prediksi['source'] ?? null,
            'prediksi_dibaca_ms' => $prediksi['time_ms'] ?? null,
            'kategori_prediksi' => $kategoriPrediksi['kategori'] ?? null,
            'kategori_prediksi_skor' => $kategoriPrediksi['score'] ?? null,
            'kategori_prediksi_sumber' => $kategoriPrediksi['source'] ?? null,
            'prediksi_kategori_ms' => $kategoriPrediksi['time_ms'] ?? null,
        ]);

        return redirect()->route('pengaduan.index');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        $pengaduan->delete();
        return redirect()->route('pengaduan.index');
    }

    /**
     * Tampilkan detail voting dari Random Forest decision trees
     */
    public function showTreeVoting(Pengaduan $pengaduan)
    {
        // Dapatkan detail tree voting dari ML service
        $mlService = app(MachineLearningService::class);
        $treeVoting = $mlService->getRandomForestTreesDetail($pengaduan);
        
        return view('pengaduan.tree-voting', compact('pengaduan', 'treeVoting'));
    }

    /**
     * Simpan pengaduan dari landing page (tanpa login).
     * Di-assign ke kategori default "Umum" dan user pertama/aktif.
     */
    public function storeLanding(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subjek' => 'required|string|max:255',
            'detail' => 'required|string',
        ]);

        $prediksi = app(DibacaPredictor::class)
            ->predict($request->subjek, $request->detail);

        $kategoriPrediksi = app(KategoriPredictor::class)
            ->predict($request->subjek, $request->detail);
        $kategoriId = $this->resolveKategoriId($kategoriPrediksi['kategori'] ?? null);

        // Validasi akurasi: bandingkan kategori yang dipilih user dengan prediksi AI
        $kategoriDipilih = $request->subjek;
        $kategoriAI = $kategoriPrediksi['kategori'] ?? null;
        $skorAI = $kategoriPrediksi['score'] ?? 0;
        
        // Tentukan apakah kategori akurat
        $isAkurat = ($kategoriDipilih === $kategoriAI);
        
        // Buat pesan validasi
        if ($isAkurat && $skorAI >= 0.7) {
            $pesanValidasi = "✓ Kategori yang Anda pilih sudah AKURAT dan sesuai dengan analisis AI (skor: " . round($skorAI * 100, 1) . "%).";
        } elseif ($isAkurat && $skorAI < 0.7) {
            $pesanValidasi = "⚠ Kategori yang Anda pilih SESUAI dengan prediksi AI, namun tingkat keyakinan rendah (skor: " . round($skorAI * 100, 1) . "%). Mohon pastikan detail pengaduan sudah jelas.";
        } elseif (!$isAkurat && $skorAI >= 0.7) {
            $pesanValidasi = "⚠ Kategori yang Anda pilih TIDAK SESUAI dengan analisis AI. AI memprediksi kategori '{$kategoriAI}' dengan keyakinan tinggi (skor: " . round($skorAI * 100, 1) . "%). Silakan pertimbangkan untuk memilih kategori yang lebih sesuai.";
        } else {
            $pesanValidasi = "⚠ Kategori yang Anda pilih berbeda dengan prediksi AI ('{$kategoriAI}', skor: " . round($skorAI * 100, 1) . "%). Pastikan kategori dan detail pengaduan sudah sesuai.";
        }

        // Gunakan user login, jika tidak ada ambil user pertama sebagai penanggung jawab
        $user = Auth::user() ?: User::first();
        if (!$user) {
            return back()->withErrors(['msg' => 'Tidak ada akun admin untuk menerima pengaduan.']);
        }

        $deskripsiGabungan = $request->detail .
            "\n\n--\nNama Pelapor: " . $request->nama .
            "\nEmail: " . $request->email .
            "\n\n[VALIDASI KATEGORI]\n" . $pesanValidasi;

        $pengaduan = Pengaduan::create([
            'judul' => $request->subjek,
            'deskripsi' => $deskripsiGabungan,
            'kategori_id' => $kategoriId,
            'user_id' => $user->id,
            'nama_pelapor' => $request->nama,
            'email_pelapor' => $request->email,
            'status' => 'pending',
            'prediksi_dibaca' => $prediksi['label'] ?? null,
            'prediksi_skor' => $prediksi['score'] ?? null,
            'prediksi_dibaca_sumber' => $prediksi['source'] ?? null,
            'prediksi_dibaca_ms' => $prediksi['time_ms'] ?? null,
            'kategori_prediksi' => $kategoriPrediksi['kategori'] ?? null,
            'kategori_prediksi_skor' => $kategoriPrediksi['score'] ?? null,
            'kategori_prediksi_sumber' => $kategoriPrediksi['source'] ?? null,
            'prediksi_kategori_ms' => $kategoriPrediksi['time_ms'] ?? null,
        ]);

        // 🔥 KIRIM NOTIFIKASI WHATSAPP OTOMATIS
        try {
            $whatsappService = app(WhatsAppService::class);
            $whatsappService->sendPengaduanNotification([
                'kategori' => $request->subjek,
                'kategori_prediksi' => $kategoriPrediksi['kategori'] ?? null,
                'kategori_prediksi_skor' => $kategoriPrediksi['score'] ?? 0,
                'detail' => $request->detail,
            ], [
                'nama' => $request->nama,
                'email' => $request->email,
                'phone' => $request->phone ?? null, // Jika ada field nomor HP
            ]);
        } catch (\Exception $e) {
            // Log error tapi tetap lanjutkan (jangan gagalkan pengaduan)
            \Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }

        // 🤖 AUTO-TRAINING ML: Kirim data ke model untuk training otomatis
        try {
            $mlService = app(MachineLearningService::class);
            $mlService->autoTrainWithNewData($pengaduan, [
                'nama' => $request->nama,
                'email' => $request->email,
            ]);
            \Log::info("ML auto-training triggered for pengaduan #{$pengaduan->id}");
        } catch (\Exception $e) {
            // Log error tapi tetap lanjutkan
            \Log::error('ML auto-training failed: ' . $e->getMessage());
        }

        // Tambahkan pesan validasi ke session success
        $successMessage = 'Pengaduan berhasil dikirim. ' . $pesanValidasi . ' Notifikasi WhatsApp sedang dikirim ke admin.';
        return back()->with('success', $successMessage);
    }

    private function resolveKategoriId(?string $kategoriNama): int
    {
        $nama = $kategoriNama ?: config('kategori.default', 'Umum');
        $descriptions = config('kategori.descriptions', []);
        $kategori = Kategori::firstOrCreate(
            ['nama' => $nama],
            ['deskripsi' => $descriptions[$nama] ?? 'Kategori otomatis']
        );

        return $kategori->id;
    }
}
