@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card glass-card p-4 border-0">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-light border-opacity-10">
                <div class="bg-warning bg-opacity-25 rounded-circle p-3 me-3">
                    <i class="bi bi-calendar-event text-warning fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-white mb-0">Manajemen Agenda Pemilihan</h3>
                    <p class="text-muted mb-0">Atur timeline dan tahapan resmi KPU secara dinamis.</p>
                </div>
                <div class="ms-auto">
                    <button class="btn btn-warning fw-bold px-4" data-bs-toggle="modal" data-bs-target="#addAgendaModal">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Agenda Baru
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle" style="background: transparent;">
                    <thead>
                        <tr class="text-muted" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th>Status</th>
                            <th>Agenda</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agendas as $agenda)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td>
                                @if($agenda->is_active)
                                    <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-3">Tampil</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-25 text-danger rounded-pill px-3">Disembunyikan</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-white d-block">{{ $agenda->title }}</strong>
                                <small class="text-muted">{{ Str::limit($agenda->description, 50) }}</small>
                            </td>
                            <td class="text-light">{{ $agenda->start_date->format('d M Y H:i') }}</td>
                            <td class="text-light">{{ $agenda->end_date ? $agenda->end_date->format('d M Y H:i') : '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#editAgendaModal{{ $agenda->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('panitia.agenda.destroy', $agenda->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus agenda ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editAgendaModal{{ $agenda->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content bg-dark border border-light border-opacity-10 text-white">
                                    <div class="modal-header border-bottom border-light border-opacity-10">
                                        <h5 class="modal-title">Edit Agenda</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('panitia.agenda.update', $agenda->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Judul Agenda</label>
                                                <input type="text" name="title" class="form-control bg-transparent text-white border-secondary" value="{{ $agenda->title }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted">Deskripsi</label>
                                                <textarea name="description" class="form-control bg-transparent text-white border-secondary" rows="3">{{ $agenda->description }}</textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label text-muted">Tanggal Mulai</label>
                                                    <input type="datetime-local" name="start_date" class="form-control bg-transparent text-white border-secondary" value="{{ $agenda->start_date->format('Y-m-d\TH:i') }}" required>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-label text-muted">Tanggal Selesai</label>
                                                    <input type="datetime-local" name="end_date" class="form-control bg-transparent text-white border-secondary" value="{{ $agenda->end_date ? $agenda->end_date->format('Y-m-d\TH:i') : '' }}">
                                                </div>
                                            </div>
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="active{{ $agenda->id }}" {{ $agenda->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label text-muted" for="active{{ $agenda->id }}">Tampilkan di Halaman Jadwal</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top border-light border-opacity-10">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data agenda yang ditambahkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addAgendaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark border border-light border-opacity-10 text-white">
            <div class="modal-header border-bottom border-light border-opacity-10">
                <h5 class="modal-title">Tambah Agenda Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('panitia.agenda.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Judul Agenda</label>
                        <input type="text" name="title" class="form-control bg-transparent text-white border-secondary" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Deskripsi</label>
                        <textarea name="description" class="form-control bg-transparent text-white border-secondary" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted">Tanggal Mulai</label>
                            <input type="datetime-local" name="start_date" class="form-control bg-transparent text-white border-secondary" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted">Tanggal Selesai (Opsional)</label>
                            <input type="datetime-local" name="end_date" class="form-control bg-transparent text-white border-secondary">
                        </div>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="activeNew" checked value="1">
                        <label class="form-check-label text-muted" for="activeNew">Tampilkan di Halaman Jadwal</label>
                    </div>
                </div>
                <div class="modal-footer border-top border-light border-opacity-10">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan Agenda</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
