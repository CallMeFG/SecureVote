@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('panitia.dashboard') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h3 class="fw-bold text-white mb-0">Daftar Pemilih Tetap (DPT)</h3>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card glass-card p-4 border-0 mb-4">
                <h5 class="text-primary fw-bold mb-3"><i class="bi bi-person-plus-fill"></i> Tambah Pemilih</h5>
                <form action="{{ route('panitia.dpt.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small">NIM Pemilih</label>
                        <input type="text" name="nim" class="form-control bg-dark text-white border-secondary" required>
                        @error('nim')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Email Mahasiswa</label>
                        <input type="email" name="email" class="form-control bg-dark text-white border-secondary" required>
                        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="alert alert-dark small border-secondary border-opacity-25">
                        <i class="bi bi-info-circle text-info"></i> Password default akun pemilih baru adalah <code>password123</code>.
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Tambahkan Pemilih</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card glass-card p-4 border-0">
                <h5 class="text-white fw-bold mb-3">Data Pemilih (Total: {{ $voters->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Status Memilih</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($voters as $voter)
                            <tr>
                                <td class="text-info">{{ $voter->nim }}</td>
                                <td class="text-white">{{ $voter->name }}<br><small class="text-muted">{{ $voter->email }}</small></td>
                                <td>
                                    @if($voter->is_voted)
                                        <span class="badge bg-success">Sudah Memilih</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Memilih</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('panitia.dpt.destroy', $voter->id) }}" method="POST" onsubmit="return confirm('Hapus data pemilih ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
