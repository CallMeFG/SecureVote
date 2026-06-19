@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center">
        @if($period && $period->is_active)
            <div class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3 border border-success border-opacity-25">
                <i class="bi bi-clock-history me-1"></i> PERIODE VOTING SEDANG AKTIF
            </div>
        @else
            <div class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill mb-3 border border-danger border-opacity-25">
                <i class="bi bi-door-closed me-1"></i> PERIODE VOTING DITUTUP
            </div>
        @endif
        
        <h2 class="fw-bold text-white">Kandidat Gubernur & Wakil Gubernur</h2>
        <h4 class="text-info fw-bold mb-2">Periode {{ $period ? $period->period_name : 'Belum Ditentukan' }}</h4>
        @if($period && $period->start_at && $period->end_at)
            <p class="text-light mb-3">
                <i class="bi bi-calendar-event me-1 text-primary"></i> 
                Masa Pencoblosan: <strong>{{ $period->start_at->format('d M Y') }}</strong> - <strong>{{ $period->end_at->format('d M Y') }}</strong>
            </p>
        @endif
        
        @auth
            @if(auth()->user()->is_voted)
                <div class="alert alert-success bg-success bg-opacity-10 border border-success border-opacity-25 text-white d-inline-block mx-auto mt-2">
                    <i class="bi bi-check-circle-fill text-success me-2 fs-5 align-middle"></i>
                    <span class="align-middle"><strong>TELAH MEMILIH</strong> — Terima kasih, hak suara Anda telah terkunci dengan aman.</span>
                </div>
            @endif
        @endauth
        
        <p class="text-muted mt-3">Profil para calon pemimpin yang akan membawa perubahan bagi ITSA PCR.</p>
    </div>
</div>

<div class="row justify-content-center g-4">
    @forelse($candidates as $candidate)
        <div class="col-md-6 col-lg-5">
            <div class="card glass-card h-100 border-0 rounded-4 overflow-hidden position-relative" style="transition: transform 0.3s ease; box-shadow: 0 0 30px rgba(56, 189, 248, 0.15), inset 0 0 20px rgba(255, 255, 255, 0.05);">
                
                <!-- Background Banner -->
                @if($candidate->photo_url)
                <div class="bg-dark w-100" style="background-image: linear-gradient(to bottom, rgba(11, 17, 32, 0.1), rgba(11, 17, 32, 1)), url('{{ $candidate->photo_url }}'); background-size: cover; background-position: center; height: 160px;">
                </div>
                @else
                <div class="bg-primary bg-opacity-25 w-100 d-flex align-items-center justify-content-center" style="height: 160px; background: linear-gradient(135deg, rgba(56, 189, 248, 0.2) 0%, rgba(15, 23, 42, 1) 100%);">
                    <i class="bi bi-person-bounding-box text-info opacity-50" style="font-size: 4rem;"></i>
                </div>
                @endif
                
                <!-- Number Badge (Top Right) -->
                <div class="position-absolute top-0 end-0 m-3">
                    <div class="bg-info rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 45px; height: 45px; border: 2px solid #fff;">
                        <span class="fs-5 fw-bold text-dark">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>

                <div class="card-body p-4 pt-1">
                    
                    <!-- Names & Details -->
                    <div class="text-center mb-4 mt-2">
                        <h4 class="fw-bold text-white mb-0">{{ $candidate->name }}</h4>
                        <span class="text-info small fw-bold text-uppercase tracking-wider">Calon Gubernur</span>
                        <div class="text-muted small mb-3">NIM: {{ $candidate->nim }}</div>

                        <div class="d-flex align-items-center justify-content-center my-2 opacity-50">
                            <hr class="w-25 border-light m-0">
                            <i class="bi bi-x-lg mx-3 text-light" style="font-size: 0.7rem;"></i>
                            <hr class="w-25 border-light m-0">
                        </div>

                        <h5 class="fw-bold text-light mb-0 mt-3">{{ $candidate->vice_name ?? 'Belum Ditentukan' }}</h5>
                        <span class="text-secondary small fw-bold text-uppercase tracking-wider">Calon Wakil Gubernur</span>
                        <div class="text-muted small">NIM: {{ $candidate->vice_nim ?? '-' }}</div>
                    </div>
                    
                    <!-- Visi Misi Container -->
                    <div class="bg-dark bg-opacity-50 rounded-4 p-4 border border-light border-opacity-10 mt-4">
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary bg-opacity-25 rounded p-2 me-2">
                                    <i class="bi bi-eye text-info"></i>
                                </div>
                                <h6 class="text-white fw-bold mb-0 text-uppercase tracking-wider">Visi</h6>
                            </div>
                            <p class="text-light small mb-0 lh-lg" style="text-align: justify;">{{ $candidate->vision }}</p>
                        </div>
                        
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-success bg-opacity-25 rounded p-2 me-2">
                                    <i class="bi bi-bullseye text-success"></i>
                                </div>
                                <h6 class="text-white fw-bold mb-0 text-uppercase tracking-wider">Misi</h6>
                            </div>
                            <p class="text-light small mb-0 lh-lg" style="text-align: justify;">{{ $candidate->mission }}</p>
                        </div>
                    </div>
                    
                    @auth
                        @if(auth()->user()->role->name === 'pemilih' && !auth()->user()->is_voted && $period && $period->is_active)
                            <div class="mt-4 mb-2 px-2">
                                <a href="{{ route('voting.confirm', $candidate->id) }}" class="btn btn-primary w-100 py-3 fw-bold rounded-3" style="box-shadow: 0 4px 15px rgba(56, 189, 248, 0.3);">
                                    VOTE NOMOR {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-people text-muted fs-1 mb-3 d-block"></i>
            <h5 class="text-white">Belum ada kandidat terdaftar</h5>
        </div>
    @endforelse
</div>
@endsection
