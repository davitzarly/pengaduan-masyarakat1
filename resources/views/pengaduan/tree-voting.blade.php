@extends('layouts.app')

@section('title', 'Random Forest Tree Voting Analysis')

@section('content')
<style>
    .tree-card {
        transition: all 0.3s ease;
        border-left: 4px solid #6366f1;
    }
    .tree-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .confidence-bar {
        height: 8px;
        border-radius: 4px;
        background: linear-gradient(90deg, #10b981 0%, #3b82f6 50%, #8b5cf6 100%);
        transition: width 0.5s ease;
    }
    .voting-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
    }
    .voting-badge.winner {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .voting-badge.runner-up {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    .voting-badge.other {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }
    .ensemble-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .tree-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
        margin-top: 24px;
    }
    .category-vote-bar {
        background: #f3f4f6;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 12px;
    }
    .category-vote-fill {
        height: 48px;
        display: flex;
        align-items: center;
        padding: 0 16px;
        color: white;
        font-weight: 600;
        transition: width 0.8s ease;
    }
    .model-info-badge {
        background: #f3f4f6;
        padding: 12px 20px;
        border-radius: 12px;
        display: inline-block;
        margin-right: 12px;
        margin-bottom: 12px;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted small mb-1">Random Forest Analysis</p>
            <h2 class="mb-0">🌳 Decision Trees Voting</h2>
            <p class="text-muted mb-0">Analisis detail voting dari {{ $treeVoting['model_info']['total_trees'] ?? 100 }} decision trees</p>
        </div>
        <a href="{{ route('pengaduan.show', $pengaduan) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Pengaduan Info -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">📋 Detail Pengaduan</h5>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2"><strong>Judul:</strong> {{ $pengaduan->judul }}</p>
                    <p class="mb-2"><strong>Kategori Aktual:</strong> 
                        <span class="badge bg-primary">{{ $pengaduan->kategori->nama ?? 'Tidak Ada' }}</span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Prediksi AI:</strong> 
                        <span class="badge bg-success">{{ $pengaduan->kategori_prediksi ?? 'N/A' }}</span>
                    </p>
                    <p class="mb-2"><strong>Confidence:</strong> 
                        <span class="badge bg-info">{{ number_format(($pengaduan->kategori_prediksi_skor ?? 0) * 100, 1) }}%</span>
                    </p>
                </div>
            </div>
            <div class="mt-3">
                <p class="mb-1"><strong>Deskripsi:</strong></p>
                <p class="text-muted">{{ Str::limit($pengaduan->deskripsi, 200) }}</p>
            </div>
        </div>
    </div>

    @if($treeVoting['success'])
        <!-- Ensemble Voting Summary -->
        <div class="ensemble-card">
            <h4 class="mb-3">🎯 Hasil Voting Ensemble</h4>
            <div class="row">
                <div class="col-md-8">
                    @if(isset($treeVoting['ensemble_voting']['voting_details']))
                        @foreach($treeVoting['ensemble_voting']['voting_details'] as $index => $vote)
                            <div class="category-vote-bar">
                                <div class="category-vote-fill" 
                                     style="width: {{ $vote['percentage'] }}%; 
                                            background: {{ $index === 0 ? 'linear-gradient(90deg, #10b981, #059669)' : ($index === 1 ? 'linear-gradient(90deg, #f59e0b, #d97706)' : 'linear-gradient(90deg, #6b7280, #4b5563)') }}">
                                    {{ $vote['kategori'] }} - {{ $vote['votes'] }} trees ({{ number_format($vote['percentage'], 1) }}%)
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="bg-white bg-opacity-10 rounded p-3">
                        <h5 class="mb-3">📊 Statistik</h5>
                        <p class="mb-2"><strong>Total Trees:</strong> {{ $treeVoting['ensemble_voting']['total_trees'] ?? 100 }}</p>
                        <p class="mb-2"><strong>Keputusan Final:</strong><br>
                            <span class="badge bg-light text-dark mt-1">
                                {{ $treeVoting['ensemble_voting']['final_decision']['kategori'] ?? 'N/A' }}
                            </span>
                        </p>
                        <p class="mb-0"><strong>Confidence:</strong> 
                            {{ number_format(($treeVoting['ensemble_voting']['final_decision']['percentage'] ?? 0), 1) }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Model Information -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">🤖 Informasi Model</h5>
                <div class="model-info-badge">
                    <i class="bi bi-tree"></i> <strong>Total Trees:</strong> {{ $treeVoting['model_info']['total_trees'] ?? 100 }}
                </div>
                <div class="model-info-badge">
                    <i class="bi bi-layers"></i> <strong>Max Depth:</strong> {{ $treeVoting['model_info']['max_depth'] ?? 10 }}
                </div>
                <div class="model-info-badge">
                    <i class="bi bi-grid-3x3"></i> <strong>Features:</strong> {{ $treeVoting['model_info']['n_features'] ?? 500 }}
                </div>
                @if(isset($treeVoting['fallback']) && $treeVoting['fallback'])
                    <div class="model-info-badge" style="background: #fef3c7;">
                        <i class="bi bi-exclamation-triangle text-warning"></i> <strong>Mode:</strong> Fallback (Python script tidak tersedia)
                    </div>
                @endif
            </div>
        </div>

        <!-- Individual Tree Predictions -->
        @if(isset($treeVoting['tree_predictions']) && count($treeVoting['tree_predictions']) > 0)
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">🌲 Prediksi Individual Trees</h5>
                    <p class="text-muted mb-4">Setiap decision tree memberikan vote berdasarkan analisisnya sendiri</p>
                    
                    <div class="tree-grid">
                        @foreach($treeVoting['tree_predictions'] as $tree)
                            <div class="card tree-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">Tree #{{ $tree['tree_id'] }}</h6>
                                        <span class="badge bg-secondary">Depth: {{ $tree['depth'] }}</span>
                                    </div>
                                    <p class="mb-2"><strong>Prediksi:</strong><br>
                                        <span class="badge bg-primary mt-1">{{ $tree['prediction'] }}</span>
                                    </p>
                                    <p class="mb-2"><strong>Confidence:</strong> {{ number_format($tree['confidence'] * 100, 1) }}%</p>
                                    <div class="confidence-bar" style="width: {{ $tree['confidence'] * 100 }}%"></div>
                                    @if(isset($tree['n_leaves']))
                                        <p class="text-muted small mb-0 mt-2">Leaves: {{ $tree['n_leaves'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Info:</strong> Detail voting individual trees tidak tersedia. Menggunakan data agregat dari model.
            </div>
        @endif

    @else
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Error:</strong> {{ $treeVoting['message'] ?? 'Gagal mendapatkan data tree voting' }}
        </div>
    @endif

    <!-- Explanation -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h5 class="card-title mb-3">💡 Cara Kerja Random Forest</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="text-center p-3">
                        <div class="display-4 mb-3">🌳</div>
                        <h6>1. Multiple Trees</h6>
                        <p class="text-muted small">Random Forest terdiri dari {{ $treeVoting['model_info']['total_trees'] ?? 100 }} decision trees yang independen</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3">
                        <div class="display-4 mb-3">🗳️</div>
                        <h6>2. Voting</h6>
                        <p class="text-muted small">Setiap tree memberikan vote untuk kategori yang diprediksi</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center p-3">
                        <div class="display-4 mb-3">🎯</div>
                        <h6>3. Majority Wins</h6>
                        <p class="text-muted small">Kategori dengan vote terbanyak menjadi prediksi final</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Animasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Animate confidence bars
    const bars = document.querySelectorAll('.confidence-bar');
    bars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 100);
    });

    // Animate category vote bars
    const voteBars = document.querySelectorAll('.category-vote-fill');
    voteBars.forEach((bar, index) => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 200 + (index * 100));
    });
});
</script>
@endsection
