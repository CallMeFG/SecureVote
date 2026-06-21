@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center">
    <div class="col-md-6 col-lg-5">
        <div class="card glass-card p-4 rounded-4 border-0" style="box-shadow: 0 0 30px rgba(56, 189, 248, 0.15), inset 0 0 15px rgba(255, 255, 255, 0.05); position: relative; overflow: hidden;">
            
            <!-- Dekorasi Glow Background -->
            <div class="position-absolute top-0 start-50 translate-middle" style="width: 200px; height: 100px; background: radial-gradient(ellipse, rgba(56,189,248,0.25) 0%, rgba(0,0,0,0) 70%);"></div>

            <div class="text-center mb-4 position-relative pt-2" style="z-index: 1;">
                <h4 class="fw-bold text-white mb-1">Registrasi Pemilih</h4>
                <p class="text-muted small mb-0">Himpunan Mahasiswa PCR (@mahasiswa.pcr.ac.id)</p>
            </div>
            
            <form action="{{ route('register') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3 position-relative" style="z-index: 1;">
                    <label class="form-label text-muted small fw-bold tracking-wide">Nomor Induk Mahasiswa (NIM)</label>
                    <input type="text" name="nim" class="form-control bg-dark bg-opacity-50 text-white border-secondary border-opacity-25 @error('nim') is-invalid @enderror" value="{{ old('nim') }}" placeholder="Contoh: 2501001" required style="border-radius: 0.5rem;">
                    @error('nim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 position-relative" style="z-index: 1;">
                    <label class="form-label text-muted small fw-bold tracking-wide">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control bg-dark bg-opacity-50 text-white border-secondary border-opacity-25 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: John Doe" required style="border-radius: 0.5rem;">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 position-relative" style="z-index: 1;">
                    <label class="form-label text-muted small fw-bold tracking-wide">Email PCR</label>
                    <input type="email" name="email" class="form-control bg-dark bg-opacity-50 text-white border-secondary border-opacity-25 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="john@mahasiswa.pcr.ac.id" pattern=".*@mahasiswa\.pcr\.ac\.id$" title="Gunakan email domain @mahasiswa.pcr.ac.id" required style="border-radius: 0.5rem;">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3 position-relative" style="z-index: 1;">
                    <label class="form-label text-muted small fw-bold tracking-wide">Kata Sandi Baru</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control bg-dark bg-opacity-50 text-white border-secondary border-opacity-25 @error('password') is-invalid @enderror" placeholder="Minimal 8 Karakter, Huruf Besar, Angka, Simbol" required style="border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; border-right: none;">
                        <button class="btn btn-outline-secondary border-secondary border-opacity-25 bg-dark bg-opacity-50" type="button" id="togglePassword" style="border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; border-left: none;">
                            <i class="bi bi-eye text-muted" id="toggleIcon"></i>
                        </button>
                    </div>
                    <div class="form-text text-muted" style="font-size: 0.75rem;">Kombinatorika Password: Wajib huruf besar, kecil, angka, dan simbol khusus (menambah ruang sampel tebakan).</div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4 position-relative" style="z-index: 1;">
                    <label class="form-label text-muted small fw-bold tracking-wide">Konfirmasi Kata Sandi</label>
                    <div class="input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control bg-dark bg-opacity-50 text-white border-secondary border-opacity-25" placeholder="Ulangi Kata Sandi" required style="border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; border-right: none;">
                    </div>
                </div>

                <div class="d-grid mt-4 position-relative" style="z-index: 1;">
                    <button type="submit" class="btn btn-primary fw-bold rounded-pill shadow-glow" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); border: none;">
                        Daftar
                    </button>
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="btn btn-link text-muted small p-0 text-decoration-none border-0 bg-transparent">Sudah terdaftar? Masuk di sini</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const password = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
            icon.classList.replace('text-muted', 'text-info');
        } else {
            password.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
            icon.classList.replace('text-info', 'text-muted');
        }
    });
</script>
@endsection
