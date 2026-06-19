@extends('layouts.app')

@section('content')
<div class="row min-vh-75 align-items-center">
    <div class="col-lg-6 text-lg-start text-center mt-5 mt-lg-0">
        <div class="d-inline-block bg-primary bg-opacity-10 text-info px-3 py-1 rounded-pill mb-3 fw-bold small">
            <i class="bi bi-shield-check me-1"></i> Sistem E-Voting Terenkripsi
        </div>
        <h1 class="display-3 fw-bold mb-4">
            Pemilihan Gubernur <br>
            <span class="text-gradient">ITSA PCR</span>
        </h1>
        <p class="lead text-muted mb-5 pe-lg-5">
            Selamat datang di portal resmi pemilihan Gubernur ITSA PCR. Suara Anda dijamin kerahasiaan dan keamanannya dengan teknologi enkripsi tingkat tinggi.
        </p>
        
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg shadow-lg">
                <i class="bi bi-box-arrow-in-right me-2"></i> Mulai Voting
            </a>
            <a href="{{ route('public.kandidat') }}" class="btn btn-outline-light btn-lg">
                Lihat Kandidat
            </a>
        </div>
    </div>
    <div class="col-lg-6 mt-5 mt-lg-0 d-none d-lg-block text-center">
        <!-- Modern abstract graphic representation -->
        <div class="position-relative d-inline-block">
            <div class="position-absolute top-50 start-50 translate-middle w-100 h-100 bg-primary opacity-25 rounded-circle blur-3xl" style="filter: blur(80px); width: 300px; height: 300px;"></div>
            <i class="bi bi-shield-lock-fill text-white opacity-75" style="font-size: 15rem; filter: drop-shadow(0 0 30px rgba(56,189,248,0.5));"></i>
        </div>
    </div>
</div>

<!-- Section: Tentang PCR & Tema -->
<div class="row mt-5 pt-5 pb-4 border-top border-light border-opacity-10 align-items-center">
    <div class="col-lg-6 mb-4 mb-lg-0 order-2 order-lg-1">
        <div class="card glass-card p-4 border-0 rounded-4" style="box-shadow: 0 0 20px rgba(56, 189, 248, 0.05);">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-25 p-3 rounded text-info me-3">
                    <i class="bi bi-building fs-3"></i>
                </div>
                <h3 class="fw-bold text-white mb-0">Politeknik Caltex Riau</h3>
            </div>
            <p class="text-muted lh-lg" style="text-align: justify;">
                Politeknik Caltex Riau (PCR) merupakan institusi pendidikan tinggi vokasi unggulan yang berfokus pada teknologi terapan dan inovasi digital. Menyelaraskan visi kampus dengan teknologi masa depan, <strong>SecureVote</strong> hadir sebagai wujud nyata karya mahasiswa teknik informatika.
            </p>
            <p class="text-light small mb-0"><i class="bi bi-check-circle-fill text-success me-2"></i> Empowering Future Innovators</p>
        </div>
    </div>
    <div class="col-lg-6 order-1 order-lg-2 mb-4 mb-lg-0 px-lg-5">
        <h2 class="fw-bold text-white mb-4">Membangun Ekosistem Digital <span class="text-gradient">Kampus</span></h2>
        <p class="text-muted">
            Platform pemilihan ini dirancang khusus untuk memenuhi standar integritas tinggi. Dengan mengkombinasikan dasar ilmu <strong>Matematika Diskrit (Logika Proposisional)</strong> dan arsitektur keamanan tingkat lanjut.
        </p>
    </div>
</div>

<!-- Section: Fitur SecureVote -->
<div class="row mt-4 mb-5 pb-5">
    <div class="col-12 text-center mb-5">
        <h2 class="fw-bold text-white">Keunggulan <span class="text-gradient">SecureVote</span></h2>
        <p class="text-muted">3 pilar utama integritas pemilihan kami.</p>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card glass-card h-100 p-4 border-0 text-center rounded-4" style="transition: transform 0.3s ease;">
            <div class="bg-danger bg-opacity-10 d-inline-block p-4 rounded-circle mb-4 mx-auto border border-danger border-opacity-25">
                <i class="bi bi-lock-fill text-danger fs-1"></i>
            </div>
            <h5 class="fw-bold text-white mb-3">Enkripsi AES-256</h5>
            <p class="text-muted small">Pilihan Anda dienkripsi secara penuh di *database*. Panitia KPU maupun Administrator sistem tidak dapat mengetahui siapa memilih siapa.</p>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card glass-card h-100 p-4 border-0 text-center rounded-4" style="transition: transform 0.3s ease;">
            <div class="bg-info bg-opacity-10 d-inline-block p-4 rounded-circle mb-4 mx-auto border border-info border-opacity-25">
                <i class="bi bi-cpu-fill text-info fs-1"></i>
            </div>
            <h5 class="fw-bold text-white mb-3">Logika Proposisional</h5>
            <p class="text-muted small">Menerapkan hukum *Discreate Math* berlapis. Mencegah manipulasi (*double-vote*), dan hanya memberi akses kepada mahasiswa aktif yang valid.</p>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card glass-card h-100 p-4 border-0 text-center rounded-4" style="transition: transform 0.3s ease;">
            <div class="bg-success bg-opacity-10 d-inline-block p-4 rounded-circle mb-4 mx-auto border border-success border-opacity-25">
                <i class="bi bi-activity text-success fs-1"></i>
            </div>
            <h5 class="fw-bold text-white mb-3">Transparansi Real-time</h5>
            <p class="text-muted small">Dekripsi massal hanya dapat dilakukan secara otomatis oleh sistem saat periode waktu ditutup. Jejak rekapitulasi tersimpan permanen di riwayat.</p>
        </div>
    </div>
</div>
@endsection
