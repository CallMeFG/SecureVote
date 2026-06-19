@extends('layouts.app')

@section('content')
<div class="row mb-5">
    <div class="col-12 text-center">
        <span class="badge bg-primary bg-opacity-10 text-info px-3 py-2 rounded-pill mb-3 border border-info border-opacity-25">
            <i class="bi bi-shield-check me-1"></i> Sesi Enkripsi Aman
        </span>
        <h2 class="fw-bold text-white">Tentukan Pilihan Anda</h2>
        <p class="text-muted">Pilih satu pasangan kandidat terbaik untuk masa depan ITSA PCR.</p>
    </div>
</div>

<div class="row justify-content-center g-4">
    @forelse($candidates as $candidate)
        <div class="col-md-5 col-lg-4">
            <div class="card glass-card h-100 border-0 d-flex flex-column">
                @if($candidate->photo_url)
                <div class="bg-dark text-center" style="background-image: url('{{ $candidate->photo_url }}'); background-size: cover; background-position: center; height: 180px;">
                </div>
                @endif
                <div class="card-header border-bottom border-light border-opacity-10 bg-transparent text-center pt-4 pb-0 position-relative">
                    <div class="position-absolute top-0 start-50 translate-middle">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 40px; height: 40px; border: 2px solid var(--primary-bg);">
                            <span class="fw-bold text-white">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h5 class="card-title fw-bold text-white mb-0">{{ $candidate->name }}</h5>
                        <p class="text-muted small mb-2">&</p>
                        <h6 class="card-title fw-bold text-light mb-3">{{ $candidate->vice_name ?? '-' }}</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4 flex-grow-1">
                        <p class="text-white-50 text-center lh-sm small">
                            <i class="bi bi-quote fs-5 text-info opacity-50"></i><br>
                            {{ Str::limit($candidate->vision, 100) }}
                        </p>
                    </div>
                    
                    <div class="mt-auto">
                        <a href="{{ route('voting.confirm', $candidate->id) }}" class="btn btn-outline-info w-100 py-3 fw-bold rounded-3">
                            VOTE NOMOR {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-inbox text-muted fs-1 mb-3 d-block"></i>
            <h4 class="text-white">Data Kandidat Kosong</h4>
            <p class="text-muted">KPU belum menetapkan daftar kandidat.</p>
        </div>
    @endforelse
</div>
@endsection
