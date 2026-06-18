@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center mt-5">
        <h1 class="display-1 text-danger fw-bold"><i class="bi bi-shield-lock-fill"></i> 403</h1>
        <h3 class="text-danger">Akses Ditolak</h3>
        <p class="lead mt-3">{{ session('error') ?? 'Anda tidak memiliki hak akses.' }}</p>
        <p class="text-muted small">Kegagalan validasi proposisi logika (¬v ∨ ¬t).</p>
        <a href="{{ route('public.beranda') }}" class="btn btn-outline-primary mt-3">Kembali ke Beranda</a>
    </div>
</div>
@endsection
