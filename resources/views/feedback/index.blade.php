@extends('layouts.app')

@section('title', 'Monitoring Feedback')

@section('content')
    <div>
        <p class="text-muted small mb-1">Monitoring Feedback</p>
        <h2 class="mb-0">Evaluasi & Survei</h2>
        <p class="text-muted mb-0">Ringkasan penilaian kepuasan masyarakat.</p>
    </div>
    <div>
        <div class="input-group">
            <input type="text" value="{{ route('feedback.public.create') }}" id="surveyLink" class="form-control" readonly style="max-width: 300px;">
            <button onclick="copyLink()" class="btn btn-primary"><i class="bi bi-clipboard"></i> Salin Link Survei</button>
        </div>
        <script>
            function copyLink() {
                var copyText = document.getElementById("surveyLink");
                copyText.select();
                copyText.setSelectionRange(0, 99999); 
                navigator.clipboard.writeText(copyText.value);
                alert("Link survei berhasil disalin: " + copyText.value);
            }
        </script>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <small class="text-muted">Rata-rata Rating</small>
            <h4 class="mb-0">{{ number_format($average ?? 0, 2) }}/5</h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <small class="text-muted">Total Respon</small>
            <h4 class="mb-0">{{ $count ?? 0 }}</h4>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Pengisi</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
            @forelse($feedbacks as $fb)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($fb->user)
                            <span class="badge bg-primary">Petugas</span> {{ $fb->user->name }}
                        @else
                            <span class="badge bg-secondary">Tamu</span> {{ $fb->guest_name ?? 'Anonim' }}
                        @endif
                    </td>
                    <td>{{ $fb->rating }}</td>
                    <td>{{ $fb->comment ?? '-' }}</td>
                    <td>{{ $fb->created_at ? $fb->created_at->format('Y-m-d H:i') : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-4">Belum ada feedback.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
