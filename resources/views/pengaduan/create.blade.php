@extends('layouts.app')

@section('title', 'Buat Pengaduan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted small mb-1">Pengaduan Baru</p>
        <h2 class="mb-0">Sampaikan Keluhan Anda</h2>
        <p class="text-muted mb-0">Isi detail pengaduan agar mudah ditindaklanjuti.</p>
    </div>
    <a href="{{ route('pengaduan.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0">Formulir Pengaduan</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Periksa kembali isian berikut:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('pengaduan.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Judul Pengaduan</label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" placeholder="Contoh: Jalan rusak di RT 05" required>
                        <div class="form-text">Subjek digunakan untuk klasifikasi otomatis.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="6" placeholder="Tuliskan kronologi dan lokasi kejadian" required>{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('pengaduan.index') }}" class="btn btn-light">Batal</a>
                        <button class="btn btn-primary">Kirim Pengaduan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase fw-semibold mb-3">Tips Pengisian</h6>
                <ul class="list-unstyled small text-muted mb-0">
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-1"></i> Cantumkan lokasi atau waktu kejadian.</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-1"></i> Subjek yang jelas membantu klasifikasi otomatis.</li>
                    <li class="mb-2"><i class="bi bi-check-circle text-success me-1"></i> Deskripsi jelas membantu proses verifikasi.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
