@extends('layouts.app')

@section('title', 'Edit Pengaduan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted small mb-1">Perbarui Pengaduan</p>
        <h2 class="mb-0">{{ $pengaduan->judul }}</h2>
        <p class="text-muted mb-0">Sesuaikan detail dan status tindak lanjut.</p>
    </div>
    <a href="{{ route('pengaduan.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
</div>

<div class="card shadow-sm border-0">
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

        <form action="{{ route('pengaduan.update', $pengaduan) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-lg-8">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $pengaduan->judul) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="6" required>{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" {{ old('status', $pengaduan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="diproses" {{ old('status', $pengaduan->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ old('status', $pengaduan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        <div class="form-text">Kategori ditentukan otomatis dari subjek dan deskripsi.</div>
                    </div>
                    <div class="mb-3">
                        <label for="dibaca_verifikasi" class="form-label">Verifikasi Dibaca</label>
                        <select class="form-select" id="dibaca_verifikasi" name="dibaca_verifikasi">
                            <option value="">-- Pilih --</option>
                            <option value="Y" {{ old('dibaca_verifikasi', $pengaduan->dibaca_verifikasi) == 'Y' ? 'selected' : '' }}>Dibaca</option>
                            <option value="N" {{ old('dibaca_verifikasi', $pengaduan->dibaca_verifikasi) == 'N' ? 'selected' : '' }}>Tidak Dibaca</option>
                        </select>
                        <div class="form-text">Gunakan untuk validasi akurasi prediksi.</div>
                    </div>
                    @if($pengaduan->kategori_prediksi)
                        <div class="alert alert-info small">
                            Prediksi kategori: <strong>{{ $pengaduan->kategori_prediksi }}</strong>
                            @if($pengaduan->kategori_prediksi_skor !== null)
                                ({{ number_format($pengaduan->kategori_prediksi_skor * 100, 1) }}%)
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('pengaduan.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
