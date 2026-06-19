@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h3 class="fw-bold text-white mb-0">Manajemen Akun Panitia KPU</h3>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card glass-card p-4 border-0 mb-4">
                <h5 class="text-info fw-bold mb-3"><i class="bi bi-person-badge"></i> Tambah Akun Panitia</h5>
                <form action="{{ route('admin.panitia.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small">Username/NIM Panitia</label>
                        <input type="text" name="nim" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Email</label>
                        <input type="email" name="email" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Kata Sandi (Min 6 Karakter)</label>
                        <input type="password" name="password" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                    <button type="submit" class="btn btn-info w-100 fw-bold">Tambahkan Panitia</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card glass-card p-4 border-0">
                <h5 class="text-white fw-bold mb-3">Daftar Akun Panitia Terdaftar</h5>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Username / NIM</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($panitias as $panitia)
                            <tr>
                                <td class="text-info">{{ $panitia->nim }}</td>
                                <td class="text-white">{{ $panitia->name }}</td>
                                <td class="text-muted">{{ $panitia->email }}</td>
                                <td>
                                    <form action="{{ route('admin.panitia.destroy', $panitia->id) }}" method="POST" onsubmit="return confirm('Hapus akun panitia ini?')">
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
