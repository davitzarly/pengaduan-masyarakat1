@extends('layouts.app')

@section('title', 'Feedback Petugas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted small mb-1">Feedback Petugas</p>
        <h2 class="mb-0">Penilaian Usability</h2>
        <p class="text-muted mb-0">Berikan penilaian singkat untuk meningkatkan sistem.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
        <style>
            .rating-group {
                display: inline-flex;
                flex-direction: row-reverse;
                gap: 0.5rem;
            }
            .rating-group input {
                display: none;
            }
            .rating-group label {
                font-size: 2rem;
                color: #ddd;
                cursor: pointer;
                transition: color 0.2s;
            }
            .rating-group input:checked ~ label,
            .rating-group label:hover,
            .rating-group label:hover ~ label {
                color: #ffc107;
            }
        </style>

        <form method="POST" action="{{ route('feedback.store') }}" class="py-3">
            @csrf
            
            <div class="text-center mb-5">
                <h5 class="mb-3 fw-bold text-dark">Seberapa puas Anda dengan sistem ini?</h5>
                <div class="rating-group">
                    <input type="radio" name="rating" id="star5" value="5" required><label for="star5" title="Sangat Puas"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="rating" id="star4" value="4"><label for="star4" title="Puas"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="rating" id="star3" value="3"><label for="star3" title="Cukup"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="rating" id="star2" value="2"><label for="star2" title="Kurang"><i class="bi bi-star-fill"></i></label>
                    <input type="radio" name="rating" id="star1" value="1"><label for="star1" title="Sangat Kurang"><i class="bi bi-star-fill"></i></label>
                </div>
                <div class="form-text mt-2">Pilih bintang untuk memberikan penilaian</div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Apa yang bisa kami tingkatkan?</label>
                <textarea name="comment" class="form-control bg-light border-0 px-3 py-3" rows="5" placeholder="Ceritakan pengalaman Anda menggunakan sistem ini, apa yang sudah baik dan apa yang perlu diperbaiki..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3">
                <i class="bi bi-send-fill me-2"></i> Kirim Masukan
            </button>
        </form>
</div>
@endsection
