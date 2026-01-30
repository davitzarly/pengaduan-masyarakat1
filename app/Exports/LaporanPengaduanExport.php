<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPengaduanExport implements FromCollection, WithHeadings, WithMapping
{
    private Collection $pengaduans;

    public function __construct(Collection $pengaduans)
    {
        $this->pengaduans = $pengaduans;
    }

    public function collection()
    {
        return $this->pengaduans;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul',
            'Kategori',
            'Pelapor',
            'Status',
            'Prediksi Dibaca',
            'Skor Dibaca',
            'Prediksi Kategori',
            'Skor Kategori',
            'Tanggal',
        ];
    }

    public function map($pengaduan): array
    {
        return [
            $pengaduan->id,
            $pengaduan->judul,
            optional($pengaduan->kategori)->nama ?? '-',
            optional($pengaduan->user)->name ?? 'Guest',
            $pengaduan->status,
            $pengaduan->prediksi_dibaca ?? '-',
            $pengaduan->prediksi_skor ?? null,
            $pengaduan->kategori_prediksi ?? '-',
            $pengaduan->kategori_prediksi_skor ?? null,
            optional($pengaduan->created_at)?->format('Y-m-d H:i:s') ?? '-',
        ];
    }
}
