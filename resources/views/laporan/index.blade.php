@extends('layouts.app')

@section('title', 'Laporan Pengaduan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted small mb-1">Laporan</p>
        <h2 class="mb-0">Laporan Pengaduan</h2>
        <p class="text-muted mb-0">Filter data dan ekspor laporan.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.laporan.export.pdf', request()->query()) }}" class="btn btn-outline-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
        </a>
        <a href="{{ route('admin.laporan.export.excel', request()->query()) }}" class="btn btn-outline-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Excel
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select">
                    <option value="">Semua</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ (string) request('kategori_id') === (string) $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">
                <button class="btn btn-primary">Terapkan Filter</button>
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <small class="text-muted">Total</small>
            <h4 class="mb-0">{{ $total }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <small class="text-muted">Pending</small>
            <h4 class="mb-0">{{ $pending }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <small class="text-muted">Diproses</small>
            <h4 class="mb-0">{{ $diproses }}</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <small class="text-muted">Selesai</small>
            <h4 class="mb-0">{{ $selesai }}</h4>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <small class="text-muted">Akurasi Kategori</small>
            <h4 class="mb-0">{{ number_format($kategoriAccuracy ?? 0, 2) }}%</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <small class="text-muted">Akurasi Dibaca</small>
            <h4 class="mb-0">{{ number_format($dibacaAccuracy ?? 0, 2) }}%</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <small class="text-muted">Rata-rata Kategori</small>
            <h4 class="mb-0">{{ $avgKategoriMs }} ms</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <small class="text-muted">Rata-rata Dibaca</small>
            <h4 class="mb-0">{{ $avgDibacaMs }} ms</h4>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Pelapor</th>
                    <th>Status</th>
                    <th>Prediksi</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
            @forelse($pengaduans as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ Str::limit($p->judul, 60) }}</td>
                    <td>{{ optional($p->kategori)->nama ?? '-' }}</td>
                    <td>{{ optional($p->user)->name ?? 'Guest' }}</td>
                    <td>{{ ucfirst($p->status) }}</td>
                    <td>
                        {{ $p->kategori_prediksi ?? '-' }}
                        @if($p->kategori_prediksi_skor !== null)
                            ({{ number_format($p->kategori_prediksi_skor * 100, 1) }}%)
                        @endif
                    </td>
                    <td>{{ $p->created_at ? $p->created_at->format('Y-m-d') : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-4">Tidak ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
