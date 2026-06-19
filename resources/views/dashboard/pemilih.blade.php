@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card glass-card p-4 border-0">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-light border-opacity-10">
                <div class="bg-primary bg-opacity-25 rounded-circle p-3 me-3">
                    <i class="bi bi-person-fill text-info fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-white mb-0">Panel Pemilih</h3>
                    <p class="text-muted mb-0">Selamat datang, <strong class="text-light">{{ auth()->user()->name }}</strong> ({{ auth()->user()->nim }})</p>
                </div>
            </div>
            
            <div class="row g-4 mt-1">
                <div class="col-lg-5">
                    <div class="card bg-dark bg-opacity-50 border-0 h-100 p-4 rounded-4 shadow-sm" style="border: 1px solid rgba(255,255,255,0.05) !important;">
                        <h5 class="text-white fw-bold mb-4">Status Hak Pilih</h5>
                        
                        @if(auth()->user()->is_voted)
                            <div class="text-center py-4">
                                <div class="d-inline-block bg-success bg-opacity-10 p-4 rounded-circle mb-3">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem; filter: drop-shadow(0 0 15px rgba(25,135,84,0.4));"></i>
                                </div>
                                <h3 class="text-success fw-bold">SUDAH MEMILIH</h3>
                                <p class="text-muted mt-2">Terima kasih atas partisipasi Anda.</p>
                                <button class="btn btn-outline-secondary w-100 mt-3 rounded-pill" disabled>Selesai</button>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="d-inline-block bg-warning bg-opacity-10 p-4 rounded-circle mb-3">
                                    <i class="bi bi-exclamation-circle-fill text-warning" style="font-size: 4rem; filter: drop-shadow(0 0 15px rgba(255,193,7,0.4));"></i>
                                </div>
                                <h3 class="text-warning fw-bold">BELUM MEMILIH</h3>
                                <p class="text-muted mt-2">Hak pilih Anda belum digunakan.</p>
                                <a href="{{ route('voting.index') }}" class="btn btn-primary btn-lg w-100 mt-3 rounded-pill shadow-lg">Gunakan Hak Pilih</a>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-lg-7">
                    <div class="card bg-dark bg-opacity-50 border-0 h-100 p-4 rounded-4 shadow-sm" style="border: 1px solid rgba(255,255,255,0.05) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-white fw-bold mb-0">Riwayat Aktivitas Terakhir</h5>
                            <i class="bi bi-clock-history text-muted"></i>
                        </div>
                        
                        <div class="list-group list-group-flush bg-transparent">
                            @forelse(auth()->user()->activityLogs()->latest()->take(4)->get() as $log)
                                <div class="list-group-item bg-transparent border-light border-opacity-10 px-0 py-3">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div>
                                            <h6 class="text-light fw-bold mb-1">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</h6>
                                            <p class="text-muted small mb-0">{{ $log->description }}</p>
                                        </div>
                                        <small class="text-muted bg-dark px-2 py-1 rounded">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <p>Belum ada aktivitas tercatat.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
