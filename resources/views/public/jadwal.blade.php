@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center min-vh-50">
    <div class="col-md-8 text-center mt-5">
        <h2 class="fw-bold mb-4 text-white">Status Periode Pemilihan</h2>
        
        <div class="card glass-card p-5 mt-4 border-0 position-relative overflow-hidden">
            @if($period)
                @if($period->is_active)
                    <div class="position-absolute top-50 start-50 translate-middle bg-success opacity-10 rounded-circle blur-3xl" style="filter: blur(80px); width: 300px; height: 300px; z-index: 0;"></div>
                    <div class="position-relative" style="z-index: 1;">
                        <i class="bi bi-clock-history text-success fs-1 mb-3 d-block"></i>
                        <h1 class="text-success fw-bold display-4 mb-3" style="text-shadow: 0 0 20px rgba(25, 135, 84, 0.5);">SEDANG BERLANGSUNG</h1>
                        <p class="lead text-light mb-2">Waktu pencoblosan saat ini dibuka.</p>
                        <div class="d-inline-block bg-dark bg-opacity-50 border border-light border-opacity-10 rounded-pill px-4 py-2 mb-4">
                            <i class="bi bi-calendar2-range text-info me-2"></i>
                            <span class="text-white">{{ $period->start_at->format('d M Y') }}</span>
                            <span class="text-muted mx-2">s/d</span>
                            <span class="text-white">{{ $period->end_at->format('d M Y') }}</span>
                        </div>
                        <br>
                        
                        @auth
                            @if(auth()->user()->is_voted)
                                <div class="alert alert-success bg-success bg-opacity-10 border border-success border-opacity-25 text-white d-inline-block mt-2 shadow-sm rounded-pill px-4">
                                    <i class="bi bi-check-circle-fill text-success me-2 fs-5 align-middle"></i>
                                    <span class="align-middle"><strong>TELAH MEMILIH</strong> — Suara Anda sudah tersimpan.</span>
                                </div>
                            @else
                                <a href="{{ route('voting.index') }}" class="btn btn-success btn-lg px-5 fw-bold rounded-pill mt-2">
                                    Berikan Suara Anda Sekarang
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-success btn-lg px-5 fw-bold rounded-pill mt-2">
                                Masuk untuk Memilih
                            </a>
                        @endauth
                    </div>
                @else
                    <div class="position-absolute top-50 start-50 translate-middle bg-danger opacity-10 rounded-circle blur-3xl" style="filter: blur(80px); width: 300px; height: 300px; z-index: 0;"></div>
                    <div class="position-relative" style="z-index: 1;">
                        <i class="bi bi-door-closed text-danger fs-1 mb-3 d-block"></i>
                        <h1 class="text-danger fw-bold display-4 mb-3" style="text-shadow: 0 0 20px rgba(220, 53, 69, 0.5);">PERIODE DITUTUP</h1>
                        <p class="lead text-muted">Jadwal pencoblosan telah berakhir atau belum dimulai oleh panitia.</p>
                    </div>
                @endif
            @else
                <div class="position-relative" style="z-index: 1;">
                    <i class="bi bi-calendar-x text-secondary fs-1 mb-3 d-block"></i>
                    <h1 class="text-secondary fw-bold display-4 mb-3">BELUM DITETAPKAN</h1>
                    <p class="text-muted">Jadwal resmi pemilihan belum diumumkan oleh panitia KPU.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
