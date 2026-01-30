<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPengaduanExport;
use App\Models\Kategori;
use App\Models\Pengaduan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildQuery($request);
        $pengaduans = $query->get();

        $total = $pengaduans->count();
        $pending = $pengaduans->where('status', 'pending')->count();
        $diproses = $pengaduans->where('status', 'diproses')->count();
        $selesai = $pengaduans->where('status', 'selesai')->count();

        $avgDibacaMs = (int) round($pengaduans->avg('prediksi_dibaca_ms') ?? 0);
        $avgKategoriMs = (int) round($pengaduans->avg('prediksi_kategori_ms') ?? 0);

        $kategoriAccuracy = $this->kategoriAccuracy($request);
        $dibacaAccuracy = $this->dibacaAccuracy($request);

        $kategoris = Kategori::orderBy('nama')->get();

        return view('laporan.index', compact(
            'pengaduans',
            'kategoris',
            'total',
            'pending',
            'diproses',
            'selesai',
            'avgDibacaMs',
            'avgKategoriMs',
            'kategoriAccuracy',
            'dibacaAccuracy'
        ));
    }

    public function exportPdf(Request $request)
    {
        $pengaduans = $this->buildQuery($request)->get();

        $pdf = Pdf::loadView('laporan.pdf', [
            'pengaduans' => $pengaduans,
            'filters' => $request->all(),
        ]);

        return $pdf->download('laporan-pengaduan.pdf');
    }

    public function exportExcel(Request $request)
    {
        $pengaduans = $this->buildQuery($request)->get();

        return Excel::download(new LaporanPengaduanExport($pengaduans), 'laporan-pengaduan.xlsx');
    }

    private function buildQuery(Request $request)
    {
        $query = Pengaduan::with(['kategori', 'user'])->orderByDesc('created_at');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        return $query;
    }

    private function kategoriAccuracy(Request $request): float
    {
        $query = $this->buildQuery($request);
        $base = clone $query;
        $total = $base->whereNotNull('kategori_prediksi')->count();
        if ($total === 0) {
            return 0.0;
        }

        $akurasi = DB::table('pengaduans')
            ->join('kategoris', 'kategoris.id', '=', 'pengaduans.kategori_id')
            ->when($request->filled('tanggal_mulai'), function ($q) use ($request) {
                $q->whereDate('pengaduans.created_at', '>=', $request->tanggal_mulai);
            })
            ->when($request->filled('tanggal_selesai'), function ($q) use ($request) {
                $q->whereDate('pengaduans.created_at', '<=', $request->tanggal_selesai);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('pengaduans.status', $request->status);
            })
            ->when($request->filled('kategori_id'), function ($q) use ($request) {
                $q->where('pengaduans.kategori_id', $request->kategori_id);
            })
            ->whereNotNull('pengaduans.kategori_prediksi')
            ->whereColumn('kategoris.nama', 'pengaduans.kategori_prediksi')
            ->count();

        return round(($akurasi / $total) * 100, 2);
    }

    private function dibacaAccuracy(Request $request): float
    {
        $query = $this->buildQuery($request);
        $base = clone $query;
        $total = $base->whereNotNull('dibaca_verifikasi')->count();
        if ($total === 0) {
            return 0.0;
        }

        $akurasi = $this->buildQuery($request)
            ->whereNotNull('dibaca_verifikasi')
            ->whereColumn('prediksi_dibaca', 'dibaca_verifikasi')
            ->count();

        return round(($akurasi / $total) * 100, 2);
    }
}
