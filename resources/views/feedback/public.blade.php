
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei Kepuasan Masyarakat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .survey-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 90%;
            overflow: hidden;
        }
        .survey-header {
            background: #3b61ff;
            color: #fff;
            padding: 30px;
            text-align: center;
        }
        .rating-group {
            display: inline-flex;
            flex-direction: row-reverse;
            gap: 0.5rem;
            justify-content: center;
        }
        .rating-group input { display: none; }
        .rating-group label {
            font-size: 2.5rem;
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
</head>
<body>

    <div class="survey-card">
        <div class="survey-header">
            <h3 class="fw-bold mb-1">Survei Kepuasan</h3>
            <p class="mb-0 opacity-75">Bantu kami meningkatkan layanan DISDUKCAPIL</p>
        </div>
        <div class="p-4 p-md-5">
            @if(session('success'))
                <div class="alert alert-success text-center border-0 bg-success text-white">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
                <div class="text-center mt-4">
                    <a href="{{ url('/') }}" class="btn btn-outline-primary">Kembali ke Beranda</a>
                </div>
            @else
                <form method="POST" action="{{ route('feedback.public.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Nama (Opsional)</label>
                        <input type="text" name="guest_name" class="form-control form-control-lg bg-light border-0" placeholder="Nama Anda (boleh dikosongkan)">
                    </div>

                    <div class="text-center mb-5">
                        <h5 class="mb-3 fw-bold text-dark">Seberapa puas Anda dengan pelayanan kami?</h5>
                        <div class="rating-group w-100 justify-content-center">
                            <input type="radio" name="rating" id="star5" value="5" required><label for="star5" title="Sangat Puas"><i class="bi bi-star-fill"></i></label>
                            <input type="radio" name="rating" id="star4" value="4"><label for="star4" title="Puas"><i class="bi bi-star-fill"></i></label>
                            <input type="radio" name="rating" id="star3" value="3"><label for="star3" title="Cukup"><i class="bi bi-star-fill"></i></label>
                            <input type="radio" name="rating" id="star2" value="2"><label for="star2" title="Kurang"><i class="bi bi-star-fill"></i></label>
                            <input type="radio" name="rating" id="star1" value="1"><label for="star1" title="Sangat Kurang"><i class="bi bi-star-fill"></i></label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Masukan & Saran</label>
                        <textarea name="comment" class="form-control bg-light border-0 px-3 py-3" rows="4" placeholder="Tuliskan kritik dan saran Anda di sini..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm">
                        Kirim Survei
                    </button>
                </form>
            @endif
        </div>
    </div>

</body>
</html>
