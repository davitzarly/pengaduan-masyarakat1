@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@section('content')
@php
    $statusClass = match($pengaduan->status) {
        'pending' => 'bg-warning text-dark',
        'diproses' => 'bg-info text-dark',
        'selesai' => 'bg-success',
        default => 'bg-secondary'
    };
    $prediksiLabel = match($pengaduan->prediksi_dibaca) {
        'Y' => 'Dibaca',
        'N' => 'Tidak Dibaca',
        default => '-'
    };
    $prediksiSkor = $pengaduan->prediksi_skor !== null
        ? number_format($pengaduan->prediksi_skor * 100, 1) . '%'
        : '-';
@endphp

<div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
    <div class="mb-2">
        <p class="text-muted small mb-1">Detail Pengaduan</p>
        <h2 class="mb-0">{{ $pengaduan->judul }}</h2>
    </div>
    <span class="badge {{ $statusClass }} px-3 py-2 text-uppercase">{{ ucfirst($pengaduan->status ?? 'pending') }}</span>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="bi bi-folder"></i></span>
                    <div>
                        <small class="text-muted">Kategori</small>
                        <div class="fw-semibold">{{ optional($pengaduan->kategori)->nama ?? '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="bi bi-person"></i></span>
                    <div>
                        <small class="text-muted">Pelapor</small>
                        <div class="fw-semibold">{{ optional($pengaduan->user)->name ?? 'Guest' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="bi bi-calendar-event"></i></span>
                    <div>
                        <small class="text-muted">Dibuat</small>
                        <div class="fw-semibold">{{ $pengaduan->created_at ? $pengaduan->created_at->format('d M Y, H:i') : '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="bi bi-graph-up"></i></span>
                    <div>
                        <small class="text-muted">Prediksi Dibaca</small>
                        <div class="fw-semibold">{{ $prediksiLabel }} ({{ $prediksiSkor }})</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="bi bi-tags"></i></span>
                    <div>
                        <small class="text-muted">Prediksi Kategori</small>
                        <div class="fw-semibold">
                            {{ $pengaduan->kategori_prediksi ?? '-' }}
                            @if($pengaduan->kategori_prediksi_skor !== null)
                                ({{ number_format($pengaduan->kategori_prediksi_skor * 100, 1) }}%)
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="bi bi-stopwatch"></i></span>
                    <div>
                        <small class="text-muted">Waktu Klasifikasi</small>
                        <div class="fw-semibold">{{ $pengaduan->prediksi_kategori_ms ?? 0 }} ms</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center">
                    <span class="text-muted me-2"><i class="bi bi-clipboard-check"></i></span>
                    <div>
                        <small class="text-muted">Verifikasi Dibaca</small>
                        <div class="fw-semibold">{{ $pengaduan->dibaca_verifikasi ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="fs-6 lh-lg">{!! nl2br(e($pengaduan->deskripsi)) !!}</div>
        
        <!-- Random Forest Tree Voting Details -->
        @if(isset($treeVoting) && $treeVoting['success'] && isset($treeVoting['ensemble_voting']['voting_details']))
            <hr class="my-4">
            <div class="mt-4">
                <h6 class="mb-3"><i class="bi bi-diagram-3 me-2"></i>Detail Voting Random Forest ({{ $treeVoting['ensemble_voting']['total_trees'] ?? 100 }} Trees)</h6>
                
                <div class="row g-2">
                    @foreach($treeVoting['ensemble_voting']['voting_details'] as $index => $vote)
                        @php
                            $badgeClass = match($index) {
                                0 => 'bg-success',
                                1 => 'bg-warning text-dark',
                                2 => 'bg-info text-dark',
                                default => 'bg-secondary'
                            };
                            $icon = match($index) {
                                0 => '🥇',
                                1 => '🥈',
                                2 => '🥉',
                                default => '🌳'
                            };
                        @endphp
                        
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge {{ $badgeClass }} px-2 py-1">{{ $icon }} #{{ $index + 1 }}</span>
                                        <span class="text-muted small">{{ $vote['votes'] }} trees</span>
                                    </div>
                                    <h6 class="mb-2">{{ $vote['kategori'] }}</h6>
                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar {{ str_replace('bg-', 'bg-', $badgeClass) }}" 
                                             role="progressbar" 
                                             style="width: {{ $vote['percentage'] }}%"
                                             aria-valuenow="{{ $vote['percentage'] }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">Voting:</small>
                                        <strong class="text-primary">{{ number_format($vote['percentage'], 1) }}%</strong>
                                    </div>
                                    @if(isset($vote['avg_confidence']))
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-muted">Avg Confidence:</small>
                                            <small class="text-secondary">{{ number_format($vote['avg_confidence'] * 100, 1) }}%</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="alert alert-light border mt-3 mb-0">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Cara Kerja:</strong> Random Forest terdiri dari {{ $treeVoting['ensemble_voting']['total_trees'] ?? 100 }} decision trees. 
                        Setiap tree memberikan vote untuk kategori yang diprediksi. 
                        Kategori <strong>{{ $treeVoting['ensemble_voting']['final_decision']['kategori'] ?? 'N/A' }}</strong> 
                        mendapat vote terbanyak ({{ number_format($treeVoting['ensemble_voting']['final_decision']['percentage'] ?? 0, 1) }}%) 
                        sehingga menjadi prediksi final.
                    </small>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <a href="{{ route('pengaduan.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    
    @auth
        <a href="{{ route('pengaduan.edit', $pengaduan) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> Edit</a>
        <form action="{{ route('pengaduan.destroy', $pengaduan) }}" method="POST" onsubmit="return confirm('Hapus pengaduan ini?')" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i> Hapus</button>
        </form>
    @endauth
</div>
@endsection
