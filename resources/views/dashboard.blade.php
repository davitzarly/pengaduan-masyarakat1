@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .stat-card { border-radius:12px; box-shadow:0 6px 22px rgba(0,0,0,0.06); }
    .stat-icon { font-size:28px; opacity:0.9; }
    .border-left-primary { border-left:4px solid #0d6efd; }
    .border-left-warning { border-left:4px solid #ffc107; }
    .border-left-info { border-left:4px solid #0dcaf0; }
    .border-left-success { border-left:4px solid #198754; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard Admin</h1>
        <p class="text-muted mb-0">Ringkasan singkat status pengaduan.</p>
    </div>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-outline-danger">Logout</button>
    </form>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card stat-card border-left-primary p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Total Pengaduan</small>
                    <h4 class="mb-0">{{ $totalPengaduan }}</h4>
                </div>
                <div class="text-end text-primary stat-icon">
                    <i class="bi bi-clipboard-data"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-left-warning p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Status Baru</small>
                    <h4 class="mb-0">{{ $pengaduanPending }}</h4>
                </div>
                <div class="text-end text-warning stat-icon">
                    <i class="bi bi-plus-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-left-info p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Diproses</small>
                    <h4 class="mb-0">{{ $pengaduanDiproses }}</h4>
                </div>
                <div class="text-end text-info stat-icon">
                    <i class="bi bi-gear"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-left-success p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Selesai</small>
                    <h4 class="mb-0">{{ $pengaduanSelesai }}</h4>
                </div>
                <div class="text-end text-success stat-icon">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-4">
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Akurasi Kategori</small>
                    <h4 class="mb-0">{{ number_format($kategoriAccuracy ?? 0, 2) }}%</h4>
                </div>
                <div class="text-end text-primary stat-icon">
                    <i class="bi bi-bullseye"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Akurasi Dibaca</small>
                    <h4 class="mb-0">{{ number_format($dibacaAccuracy ?? 0, 2) }}%</h4>
                </div>
                <div class="text-end text-info stat-icon">
                    <i class="bi bi-check2-square"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Waktu Klasifikasi</small>
                    <h4 class="mb-0">{{ $avgKategoriMs ?? 0 }} ms</h4>
                    <small class="text-muted">Dibaca: {{ $avgDibacaMs ?? 0 }} ms</small>
                </div>
                <div class="text-end text-warning stat-icon">
                    <i class="bi bi-stopwatch"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Feedback Petugas</small>
                    <h4 class="mb-0">{{ number_format($feedbackAverage ?? 0, 2) }}/5</h4>
                    <small class="text-muted">{{ $feedbackCount ?? 0 }} respon</small>
                </div>
                <div class="text-end text-success stat-icon">
                    <i class="bi bi-emoji-smile"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0">
                <strong>Distribusi Status</strong>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white border-0">
                <strong>Top 5 Kategori</strong>
            </div>
            <div class="card-body">
                <canvas id="kategoriChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white border-0">
        <strong>Pengaduan Terbaru</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Pelapor</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
            @forelse($recentPengaduans as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ Str::limit($p->judul, 80) }}</td>
                    <td>{{ optional($p->kategori)->nama ?? '-' }}</td>
                    <td>{{ optional($p->user)->name ?? 'Guest' }}</td>
                    <td>{{ ucfirst($p->status) }}</td>
                    <td>{{ $p->created_at ? $p->created_at->format('Y-m-d') : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-4">Belum ada pengaduan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const statusData = @json($statusBreakdown ?? ['pending'=>0,'diproses'=>0,'selesai'=>0]);
        const kategoriLabels = @json($kategoriLabels ?? []);
        const kategoriCounts = @json($kategoriCounts ?? []);

        const ctxStatus = document.getElementById('statusChart');
        if (ctxStatus) {
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Pending','Diproses','Selesai'],
                    datasets: [{
                        data: [
                            Number(statusData.pending ?? 0),
                            Number(statusData.diproses ?? 0),
                            Number(statusData.selesai ?? 0)
                        ],
                        backgroundColor: ['#ffc107','#0dcaf0','#198754']
                    }]
                },
                options: { responsive:true, maintainAspectRatio:false }
            });
        }

        const ctxKat = document.getElementById('kategoriChart');
        if (ctxKat) {
            new Chart(ctxKat, {
                type: 'bar',
                data: {
                    labels: kategoriLabels,
                    datasets: [{
                        label: 'Jumlah Pengaduan',
                        data: kategoriCounts.map(Number),
                        backgroundColor: '#5f7dfa'
                    }]
                },
                options: { responsive:true, maintainAspectRatio:false }
            });
        }
    </script>
@endsection
