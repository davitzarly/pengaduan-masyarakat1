@extends('layouts.app')

@section('title', 'Daftar Pengaduan')

@section('content')
@php
    $total = $pengaduans->count();
    $pending = $pengaduans->where('status', 'pending')->count();
    $diproses = $pengaduans->where('status', 'diproses')->count();
    $selesai = $pengaduans->where('status', 'selesai')->count();
@endphp

<div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div class="mb-3">
        <p class="text-muted small mb-1">Pengaduan Masyarakat</p>
        <h2 class="mb-1">Daftar Pengaduan</h2>
        <p class="text-muted mb-0">Pantau laporan terbaru dan tindak lanjutnya.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-primary me-2"><i class="bi bi-collection"></i></span>
                    <small class="text-muted text-uppercase fw-semibold">Total</small>
                </div>
                <h4 class="mb-0">{{ $total }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-warning text-dark me-2"><i class="bi bi-hourglass-split"></i></span>
                    <small class="text-muted text-uppercase fw-semibold">Pending</small>
                </div>
                <h4 class="mb-0">{{ $pending }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-info text-dark me-2"><i class="bi bi-gear"></i></span>
                    <small class="text-muted text-uppercase fw-semibold">Diproses</small>
                </div>
                <h4 class="mb-0">{{ $diproses }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2"><i class="bi bi-check-circle"></i></span>
                    <small class="text-muted text-uppercase fw-semibold">Selesai</small>
                </div>
                <h4 class="mb-0">{{ $selesai }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Prediksi Kategori</th>
                        <th>Pelapor</th>
                        <th>Status</th>
                        <th>Prediksi Dibaca</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($pengaduans as $p)
                    @php
                        $statusClass = match($p->status) {
                            'pending' => 'bg-warning text-dark',
                            'diproses' => 'bg-info text-dark',
                            'selesai' => 'bg-success',
                            default => 'bg-secondary'
                        };
                        $prediksiLabel = match($p->prediksi_dibaca) {
                            'Y' => 'Dibaca',
                            'N' => 'Tidak Dibaca',
                            default => '-'
                        };
                        $prediksiClass = match($p->prediksi_dibaca) {
                            'Y' => 'bg-success',
                            'N' => 'bg-secondary',
                            default => 'bg-light text-dark'
                        };
                    @endphp
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>
                            <div class="fw-semibold mb-1">
                                <a href="{{ route('pengaduan.show', $p->id) }}" class="text-decoration-none">{{ Str::limit($p->judul, 80) }}</a>
                            </div>
                            <small class="text-muted">{{ $p->created_at ? $p->created_at->format('d M Y, H:i') : '-' }}</small>
                        </td>
                        <td>{{ optional($p->kategori)->nama ?? '-' }}</td>
                        <td>
                            {{ $p->kategori_prediksi ?? '-' }}
                            @if($p->kategori_prediksi_skor !== null)
                                <div class="text-muted small">{{ number_format($p->kategori_prediksi_skor * 100, 1) }}%</div>
                            @endif
                        </td>
                        <td>{{ $p->nama_pelapor ?? optional($p->user)->name ?? 'Guest' }}</td>
                        <td><span class="badge {{ $statusClass }}">{{ ucfirst($p->status ?? 'pending') }}</span></td>
                        <td>
                            <span class="badge {{ $prediksiClass }}">{{ $prediksiLabel }}</span>
                            @if($p->prediksi_skor !== null)
                                <div class="text-muted small">{{ number_format($p->prediksi_skor * 100, 1) }}%</div>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group" role="group" aria-label="Aksi">
                                <a href="{{ route('pengaduan.show', $p->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                @auth
                                    <a href="{{ route('pengaduan.edit', $p->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('pengaduan.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengaduan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endauth
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted mb-2"><i class="bi bi-inbox me-1"></i> Belum ada pengaduan.</div>
                            <a href="{{ route('pengaduan.create') }}" class="btn btn-sm btn-primary">Buat Pengaduan Pertama</a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
