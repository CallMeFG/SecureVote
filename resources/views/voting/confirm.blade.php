@extends('layouts.app')

@section('content')
<div class="row justify-content-center mb-4">
    <div class="col-md-8 text-center">
        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill mb-3 border border-warning border-opacity-25">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> Konfirmasi Pilihan Anda
        </span>
        <h2 class="fw-bold text-white">Langkah Terakhir: Verifikasi Suara</h2>
        <p class="text-muted">Harap periksa kembali detail kandidat pilihan Anda dan data diri Anda di bawah ini sebelum mengirimkan suara secara permanen.</p>
    </div>
</div>

<div class="row justify-content-center g-4">
    <div class="col-md-6 col-lg-5">
        <!-- Candidate Card -->
        <div class="card glass-card h-100 border-0 rounded-4 overflow-hidden position-relative" style="box-shadow: 0 0 40px rgba(56, 189, 248, 0.2), inset 0 0 20px rgba(255, 255, 255, 0.05);">
            <div class="bg-primary text-center py-2 fw-bold tracking-wide" style="letter-spacing: 2px;">
                KANDIDAT PILIHAN ANDA
            </div>
            
            @if($candidate->photo_url)
            <div class="bg-dark w-100" style="background-image: linear-gradient(to bottom, rgba(11, 17, 32, 0.1), rgba(11, 17, 32, 1)), url('{{ $candidate->photo_url }}'); background-size: cover; background-position: center; height: 200px;">
            </div>
            @endif

            <div class="card-body p-4 pt-0 text-center">
                <div class="mt-4 mb-4">
                    <h3 class="fw-bold text-white mb-0">{{ $candidate->name }}</h3>
                    <div class="text-info small fw-bold text-uppercase tracking-wider">Calon Gubernur</div>
                    
                    <div class="d-flex align-items-center justify-content-center my-3 opacity-50">
                        <hr class="w-25 border-light m-0">
                        <i class="bi bi-link mx-3 text-light fs-5"></i>
                        <hr class="w-25 border-light m-0">
                    </div>

                    <h4 class="fw-bold text-light mb-0">{{ $candidate->vice_name ?? '-' }}</h4>
                    <div class="text-secondary small fw-bold text-uppercase tracking-wider">Calon Wakil Gubernur</div>
                </div>

                <div class="bg-dark bg-opacity-50 rounded-4 p-3 border border-light border-opacity-10 text-start">
                    <div class="mb-3">
                        <h6 class="text-info fw-bold mb-1"><i class="bi bi-eye"></i> Visi</h6>
                        <p class="text-light small mb-0 lh-base">{{ $candidate->vision }}</p>
                    </div>
                    <div>
                        <h6 class="text-success fw-bold mb-1"><i class="bi bi-bullseye"></i> Misi</h6>
                        <p class="text-light small mb-0 lh-base">{{ $candidate->mission }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <!-- Voter Info & Submit Form -->
        <div class="card glass-card border-0 rounded-4 p-4 mb-4">
            <h5 class="fw-bold text-white mb-3 border-bottom border-secondary pb-2"><i class="bi bi-person-badge"></i> Data Pemilih</h5>
            <div class="mb-2">
                <span class="text-muted small">Nomor Induk Mahasiswa:</span>
                <div class="text-white fw-bold fs-5">{{ auth()->user()->nim }}</div>
            </div>
            <div class="mb-3">
                <span class="text-muted small">Nama Lengkap:</span>
                <div class="text-white fw-bold">{{ auth()->user()->name }}</div>
            </div>
            <div class="mb-0">
                <span class="text-muted small">Periode Voting:</span>
                <div class="text-info fw-bold">{{ $period->period_name }}</div>
            </div>
        </div>

        <div class="alert alert-danger bg-danger bg-opacity-10 border border-danger border-opacity-25 text-light small mb-4 shadow-sm">
            <i class="bi bi-shield-lock-fill text-danger me-2"></i><strong>Perhatian:</strong> Suara Anda akan dienkripsi dengan algoritma AES-256. Pilihan bersifat rahasia, permanen, dan tidak dapat dibatalkan atau diubah dengan alasan apa pun.
        </div>

        <form action="{{ route('voting.submit') }}" method="POST" id="formCoblos">
            @csrf
            <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
            <button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-glow fs-5" onclick="confirmVote()">
                <i class="bi bi-envelope-paper-fill me-2"></i>KIRIM SUARA / COBLOS
            </button>
            <a href="{{ route('voting.index') }}" class="btn btn-outline-secondary w-100 py-2 mt-3 rounded-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali, saya ingin mengubah pilihan
            </a>
        </form>
    </div>
</div>

<script>
    function confirmVote() {
        Swal.fire({
            title: 'Kunci Pilihan Anda?',
            text: "Pastikan pilihan Anda sudah benar. Tindakan ini tidak dapat dibatalkan!",
            icon: 'warning',
            background: '#1e293b',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Coblos Sekarang!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading state
                Swal.fire({
                    title: 'Mengenknripsi Suara...',
                    text: 'Mohon tunggu sebentar.',
                    background: '#1e293b',
                    color: '#fff',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                document.getElementById('formCoblos').submit();
            }
        })
    }
</script>
@endsection
