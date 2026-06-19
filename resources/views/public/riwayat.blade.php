@extends('layouts.app')

@section('content')
<div class="row mb-5">
    <div class="col-12 text-center">
        <h2 class="fw-bold text-white">Transparansi Riwayat Pemilihan</h2>
        <p class="text-muted">Jejak digital seluruh hasil pemilihan dari periode-periode sebelumnya.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card glass-card p-4 border-0">
            @if(session('error'))
                <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-danger border-opacity-25">{{ session('error') }}</div>
            @endif
            
            <div class="list-group list-group-flush bg-transparent">
                @forelse($periods as $period)
                    <a href="{{ route('public.riwayat.detail', $period->id) }}" class="list-group-item list-group-item-action bg-dark bg-opacity-50 border-light border-opacity-10 py-4 px-4 mb-3 rounded-4 d-flex justify-content-between align-items-center transition-all" style="border: 1px solid rgba(255,255,255,0.05) !important;">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-archive-fill text-info fs-4 me-3"></i>
                                <h4 class="text-white fw-bold mb-0">Periode {{ $period->period_name }}</h4>
                            </div>
                            <p class="text-muted small mb-0 ps-5"><i class="bi bi-calendar-check me-1"></i> Ditutup pada: {{ $period->end_at ? $period->end_at->format('d M Y') : 'Tidak diketahui' }}</p>
                        </div>
                        <i class="bi bi-chevron-right text-muted fs-4"></i>
                    </a>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-clock-history fs-1 mb-3 d-block"></i>
                        <h5>Belum ada riwayat periode masa lalu.</h5>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
