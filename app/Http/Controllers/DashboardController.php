<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Kategori;
use App\Models\UsabilityFeedback;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPengaduan = Pengaduan::count();
        $pengaduanPending = Pengaduan::where('status', 'pending')->count();
        $pengaduanDiproses = Pengaduan::where('status', 'diproses')->count();
        $pengaduanSelesai = Pengaduan::where('status', 'selesai')->count();
        $kategoris = Kategori::withCount('pengaduan')->get();
        $kategoriTop = Kategori::withCount('pengaduan')
            ->orderByDesc('pengaduan_count')
            ->take(5)
            ->get();

        $recentPengaduans = Pengaduan::with(['kategori','user'])->latest()->take(10)->get();

        // Data for a simple status breakdown (for possible charting)
        $statusBreakdown = [
            'pending' => $pengaduanPending,
            'diproses' => $pengaduanDiproses,
            'selesai' => $pengaduanSelesai,
        ];

        $kategoriLabels = $kategoriTop->pluck('nama')->values()->all();
        $kategoriCounts = $kategoriTop->pluck('pengaduan_count')->values()->all();

        $avgDibacaMs = (int) round(Pengaduan::avg('prediksi_dibaca_ms') ?? 0);
        $avgKategoriMs = (int) round(Pengaduan::avg('prediksi_kategori_ms') ?? 0);

        $totalPrediksiKategori = Pengaduan::whereNotNull('kategori_prediksi')->count();
        $kategoriAkurat = DB::table('pengaduans')
            ->join('kategoris', 'kategoris.id', '=', 'pengaduans.kategori_id')
            ->whereNotNull('pengaduans.kategori_prediksi')
            ->whereColumn('kategoris.nama', 'pengaduans.kategori_prediksi')
            ->count();
        $kategoriAccuracy = $totalPrediksiKategori > 0
            ? round(($kategoriAkurat / $totalPrediksiKategori) * 100, 2)
            : 0.0;

        $totalDibacaVerifikasi = Pengaduan::whereNotNull('dibaca_verifikasi')->count();
        $dibacaAkurat = Pengaduan::whereNotNull('dibaca_verifikasi')
            ->whereColumn('prediksi_dibaca', 'dibaca_verifikasi')
            ->count();
        $dibacaAccuracy = $totalDibacaVerifikasi > 0
            ? round(($dibacaAkurat / $totalDibacaVerifikasi) * 100, 2)
            : 0.0;

        $feedbackAverage = UsabilityFeedback::avg('rating') ?? 0;
        $feedbackCount = UsabilityFeedback::count();

        return view('dashboard', compact(
            'totalPengaduan',
            'pengaduanPending',
            'pengaduanDiproses',
            'pengaduanSelesai',
            'kategoris',
            'recentPengaduans',
            'statusBreakdown',
            'kategoriLabels',
            'kategoriCounts',
            'avgDibacaMs',
            'avgKategoriMs',
            'kategoriAccuracy',
            'dibacaAccuracy',
            'feedbackAverage',
            'feedbackCount'
        ));
    }
}
