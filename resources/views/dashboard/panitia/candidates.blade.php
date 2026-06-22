@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('panitia.dashboard') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h3 class="fw-bold text-white mb-0">Kelola Kandidat</h3>
    </div>

    @if(!$period)
        <div class="alert alert-warning">Tidak ada periode voting yang terdaftar. Hubungi Administrator.</div>
    @else
        <div class="alert alert-info">Menampilkan kandidat untuk periode: <strong>{{ $period->period_name }}</strong>. 
            Anda hanya dapat menambah kandidat ke periode terbaru/aktif.</div>

        <div class="row">
            <div class="col-12">
                <div class="card glass-card p-4 border-0 mb-4">
                    <h5 class="text-info fw-bold mb-3"><i class="bi bi-person-plus-fill"></i> Tambah Kandidat Baru</h5>
                    <form action="{{ route('panitia.kandidat.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">NIM Gubernur</label>
                                    <input type="text" name="nim" class="form-control bg-dark text-white border-secondary" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Nama Gubernur</label>
                                    <input type="text" name="name" class="form-control bg-dark text-white border-secondary" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Email Gubernur</label>
                                    <input type="email" name="email" class="form-control bg-dark text-white border-secondary" placeholder="contoh@mahasiswa.pcr.ac.id" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">NIM Wakil</label>
                                    <input type="text" name="vice_nim" class="form-control bg-dark text-white border-secondary">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Nama Wakil</label>
                                    <input type="text" name="vice_name" class="form-control bg-dark text-white border-secondary">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Email Wakil</label>
                                    <input type="email" name="vice_email" class="form-control bg-dark text-white border-secondary" placeholder="wakil@mahasiswa.pcr.ac.id">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Visi</label>
                            <textarea name="vision" class="form-control bg-dark text-white border-secondary" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Misi</label>
                            <textarea name="mission" class="form-control bg-dark text-white border-secondary" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">URL Foto (Opsional)</label>
                            <input type="url" name="photo_url" class="form-control bg-dark text-white border-secondary" placeholder="https://...">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Kandidat</button>
                    </form>
                </div>
            </div>

            <div class="col-12">
                <div class="card glass-card p-4 border-0">
                    <h5 class="text-white fw-bold mb-3">Daftar Kandidat Terdaftar</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Gubernur</th>
                                    <th>Wakil</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($candidates as $candidate)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-info">{{ $candidate->name }}</div>
                                        <div class="small text-muted">{{ $candidate->nim }}</div>
                                    </td>
                                    <td>
                                        <div class="text-white">{{ $candidate->vice_name ?? '-' }}</div>
                                        <div class="small text-muted">{{ $candidate->vice_nim }}</div>
                                        <div class="small text-muted">{{ $candidate->vice_email }}</div>
                                    </td>
                                    <td>
                                        <form action="{{ route('panitia.kandidat.destroy', $candidate->id) }}" method="POST" onsubmit="return confirm('Hapus kandidat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada kandidat di periode ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
