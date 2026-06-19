@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center min-vh-50">
    <div class="col-md-6 text-center mt-5">
        <div class="position-relative d-inline-block mb-4">
            <div class="position-absolute top-50 start-50 translate-middle bg-danger opacity-25 rounded-circle blur-3xl" style="filter: blur(50px); width: 150px; height: 150px;"></div>
            <i class="bi bi-exclamation-octagon-fill text-danger" style="font-size: 6rem; filter: drop-shadow(0 0 20px rgba(220,53,69,0.5));"></i>
        </div>
        <h2 class="fw-bold mb-3">Akses Ditolak</h2>
        <p class="lead text-muted">{{ session('error') ?? 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini atau sesi Anda tidak valid.' }}</p>
        
        <div class="mt-5">
            <a href="{{ route('public.beranda') }}" class="btn btn-outline-light px-4 py-2">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
