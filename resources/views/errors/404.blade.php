@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<div class="text-center mt-5">
    <h1 class="display-4">404</h1>
    <p class="lead">Halaman yang Anda cari tidak ditemukan.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
</div>
@endsection
