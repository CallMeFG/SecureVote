@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success bg-success bg-opacity-10 border-0 text-success mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger bg-danger bg-opacity-10 border-0 text-danger mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            </div>
        @endif

        <div class="row g-4">
            <!-- Kolom Kiri: Kartu Profil -->
            <div class="col-lg-4">
                <div class="card glass-card p-4 border-0 text-center h-100">
                    <div class="mb-4">
                        @if(auth()->user()->photo_path)
                            <img src="{{ asset('storage/' . auth()->user()->photo_path) }}" alt="Profile Photo" class="rounded-circle object-fit-cover border border-4 border-primary border-opacity-25 mb-3" style="width: 150px; height: 150px; box-shadow: 0 0 20px rgba(13,110,253,0.15);">
                        @else
                            <div class="rounded-circle bg-dark border border-4 border-secondary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px; box-shadow: 0 0 20px rgba(255,255,255,0.05);">
                                <i class="bi bi-person-fill text-muted" style="font-size: 5rem;"></i>
                            </div>
                        @endif
                        
                        <div>
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#photoModal">
                                <i class="bi bi-camera-fill me-2"></i>{{ auth()->user()->photo_path ? 'Ganti Foto' : 'Unggah Foto' }}
                            </button>
                        </div>
                    </div>
                    
                    <h4 class="text-white fw-bold mb-1">{{ auth()->user()->name }}</h4>
                    <p class="text-info mb-4">{{ auth()->user()->nim }}</p>
                    
                    <div class="bg-dark bg-opacity-50 p-3 rounded-4 text-start border border-light border-opacity-10 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Program Studi</span>
                            <span class="text-light small fw-bold">Teknik Informatika</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Angkatan</span>
                            <span class="text-light small fw-bold">20{{ substr(auth()->user()->nim, 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Email Institusi</span>
                            <span class="text-light small fw-bold">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    
                    <div class="text-start mt-auto">
                        <small class="text-muted d-block mb-2 text-center">Status Hak Pilih</small>
                        @if(auth()->user()->is_voted)
                            <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3 py-2 w-100 fs-6"><i class="bi bi-check-circle-fill me-2"></i>Telah Digunakan</span>
                        @else
                            <span class="badge bg-warning bg-opacity-25 text-warning rounded-pill px-3 py-2 w-100 fs-6"><i class="bi bi-hourglass-split me-2"></i>Belum Digunakan</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Status & Riwayat -->
            <div class="col-lg-8">
                <div class="row g-4">
                    <!-- Status Pemilihan -->
                    <div class="col-12">
                        <div class="card glass-card border-0 p-4 rounded-4 shadow-sm h-100">
                            <h5 class="text-white fw-bold mb-4">Status Hak Pilih</h5>
                            
                            @if(auth()->user()->is_voted)
                                <div class="d-flex align-items-center bg-success bg-opacity-10 p-4 rounded-4">
                                    <i class="bi bi-check-circle-fill text-success me-4" style="font-size: 4rem; filter: drop-shadow(0 0 15px rgba(25,135,84,0.4));"></i>
                                    <div>
                                        <h3 class="text-success fw-bold mb-1">SUDAH MEMILIH</h3>
                                        <p class="text-muted mb-0">Terima kasih atas partisipasi Anda.</p>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-between bg-warning bg-opacity-10 p-4 rounded-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-circle-fill text-warning me-4" style="font-size: 3rem; filter: drop-shadow(0 0 15px rgba(255,193,7,0.4));"></i>
                                        <div>
                                            <h4 class="text-warning fw-bold mb-1">BELUM MEMILIH</h4>
                                            <p class="text-muted mb-0">Gunakan hak suara Anda segera.</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('voting.index') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-lg">Coblos Sekarang</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Riwayat -->
                    <div class="col-12">
                        <div class="card glass-card border-0 p-4 rounded-4 shadow-sm h-100">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="text-white fw-bold mb-0">Riwayat Aktivitas Terakhir</h5>
                                <i class="bi bi-clock-history text-muted"></i>
                            </div>
                            
                            <div class="list-group list-group-flush bg-transparent">
                                @forelse(auth()->user()->activityLogs()->latest()->take(3)->get() as $log)
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
</div>

<!-- Photo Upload Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark border border-light border-opacity-10 text-white">
            <div class="modal-header border-bottom border-light border-opacity-10">
                <h5 class="modal-title">Perbarui Foto Profil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pemilih.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Pilih File Gambar</label>
                        <input class="form-control bg-dark text-white border-secondary" type="file" name="photo" accept=".jpg,.jpeg,.png" required>
                        <div class="form-text text-muted">Format yang diizinkan: JPG, JPEG, PNG. Maksimal 2MB.</div>
                    </div>
                </div>
                <div class="modal-footer border-top border-light border-opacity-10">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Unggah Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
