@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Kembali</a>
        <h3 class="fw-bold text-white mb-0">Manajemen Periode Voting</h3>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card glass-card p-4 border-0 mb-4">
                <h5 class="text-primary fw-bold mb-3"><i class="bi bi-calendar-plus"></i> Buat Periode Baru</h5>
                <form action="{{ route('admin.periode.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small">Nama Periode (Contoh: 2026/2027)</label>
                        <input type="text" name="period_name" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Tanggal Mulai (Start)</label>
                        <input type="datetime-local" name="start_at" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Tanggal Selesai (End)</label>
                        <input type="datetime-local" name="end_at" class="form-control bg-dark text-white border-secondary" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Buat Periode</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card glass-card p-4 border-0">
                <h5 class="text-white fw-bold mb-3">Daftar Periode</h5>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($periods as $period)
                            <tr>
                                <td class="text-white fw-bold">{{ $period->period_name }}</td>
                                <td>
                                    <div class="small text-info">Mulai: {{ \Carbon\Carbon::parse($period->start_at)->format('d M Y, H:i') }}</div>
                                    <div class="small text-danger">Akhir: {{ \Carbon\Carbon::parse($period->end_at)->format('d M Y, H:i') }}</div>
                                </td>
                                <td>
                                    @if($period->is_active)
                                        <span class="badge bg-success shadow-glow-success px-3 py-2">AKTIF</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">DITUTUP</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($period->is_active)
                                        <form action="{{ route('admin.periode.toggle', $period->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENGUNCI (TUTUP) periode pemilihan ini? Periode yang telah ditutup tidak dapat dibuka kembali!');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold shadow-sm px-3 d-inline-flex align-items-center">
                                                <i class="bi bi-lock-fill me-2"></i> Kunci Pemilihan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small fw-bold"><i class="bi bi-check-all me-1"></i> Telah Berakhir</span>
                                    @endif
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
