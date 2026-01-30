@extends('layouts.app')

@section('title', 'Selamat Datang - Sistem Pengaduan')

@section('content')
<style>
    .hero {
        background: linear-gradient(135deg, #5f7dfa 0%, #7453d5 60%, #7f5ae6 100%);
        color: #fff;
        border-radius: 24px;
        padding: 80px 40px;
        position: relative;
        overflow: hidden;
    }
    .hero::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(255,255,255,0.28) 0%, rgba(255,255,255,0) 60%);
        top: -60px;
        right: -40px;
        filter: blur(2px);
    }
    .hero h1 { font-weight: 700; }
    .hero .lead { font-size: 1.2rem; }
    .btn-pill {
        border-radius: 999px;
        padding: 12px 26px;
        font-weight: 600;
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }
    .section-title {
        font-weight: 700;
        color: #5469d4;
        text-align: center;
    }
    .feature-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.06);
        height: 100%;
    }
    .feature-icon {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(84,105,212,0.12);
        color: #5469d4;
        font-size: 22px;
    }
    
    /* Responsive Styles */
    @media (max-width: 767.98px) {
        .hero {
            padding: 50px 24px;
            border-radius: 16px;
        }
        .hero h1 {
            font-size: 1.75rem !important;
        }
        .hero .lead {
            font-size: 1rem;
        }
        .btn-pill {
            width: 100%;
            padding: 14px 24px;
        }
        .feature-card {
            margin-bottom: 1rem;
        }
        .section-title {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 575.98px) {
        .hero {
            padding: 40px 20px;
        }
        .hero h1 {
            font-size: 1.5rem !important;
        }
        .col-md-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
    
    @media (min-width: 768px) and (max-width: 991.98px) {
        .col-md-3 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
</style>

<div class="mt-3 mb-5">
    <div class="hero text-center">
        <h1 class="display-5 mb-3">Sistem Pengaduan Masyarakat</h1>
        <p class="lead mb-4">Sampaikan keluhan dan aspirasi Anda dengan mudah dan cepat.</p>
        <div class="d-flex justify-content-center gap-3">
            <button class="btn btn-light text-primary btn-pill" data-bs-toggle="modal" data-bs-target="#modalPengaduan">Get Started</button>
        </div>
    </div>
</div>

<div class="py-4">
    <h2 class="section-title mb-4">Kenapa Menggunakan Layanan Kami?</h2>
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card feature-card p-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="feature-icon"><i class="bi bi-speedometer2"></i></div>
                    <div>
                        <h6 class="fw-semibold mb-1">Cepat & Mudah</h6>
                        <p class="text-muted mb-0">Laporkan keluhan kapan saja tanpa harus datang ke kantor.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card feature-card p-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="feature-icon"><i class="bi bi-shield-lock"></i></div>
                    <div>
                        <h6 class="fw-semibold mb-1">Aman & Terpercaya</h6>
                        <p class="text-muted mb-0">Data Anda tersimpan dengan aman dan terkelola.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card feature-card p-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="feature-icon"><i class="bi bi-journal-check"></i></div>
                    <div>
                        <h6 class="fw-semibold mb-1">Transparan</h6>
                        <p class="text-muted mb-0">Setiap pengaduan tercatat rapi dan dapat dipantau.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card feature-card p-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="feature-icon"><i class="bi bi-lightning-charge"></i></div>
                    <div>
                        <h6 class="fw-semibold mb-1">Respon Cepat</h6>
                        <p class="text-muted mb-0">Tim siap menindaklanjuti keluhan Anda segera.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Form Pengaduan --}}
<div class="modal fade" id="modalPengaduan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Pengaduan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(session('success'))
                    @php
                        $message = session('success');
                        $isAkurat = str_contains($message, '✓') || str_contains($message, 'AKURAT');
                        $alertClass = $isAkurat ? 'alert-success' : 'alert-warning';
                    @endphp
                    <div class="alert {{ $alertClass }} alert-dismissible fade show" role="alert">
                        <strong>{{ $isAkurat ? 'Berhasil!' : 'Perhatian!' }}</strong>
                        <div class="mt-2" style="white-space: pre-line;">{{ $message }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $err)
                            <div>{{ $err }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('landing.pengaduan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subjek Pengaduan</label>
                        <select name="subjek" class="form-select" required>
                            <option value="" selected disabled>Pilih kategori pengaduan</option>
                            @foreach(config('kategori.form_options', []) as $kategori)
                                <option value="{{ $kategori }}">{{ $kategori }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Pilih kategori yang paling sesuai dengan keluhan Anda.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Detail Pengaduan</label>
                        <textarea class="form-control" name="detail" rows="4" placeholder="Jelaskan keluhan Anda" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Kirim Pengaduan</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Login Admin --}}
<div class="modal fade" id="modalLoginAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Login Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Username / Email</label>
                        <input type="text" name="email" class="form-control" placeholder="admin@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
