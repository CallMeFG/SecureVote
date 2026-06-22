@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card glass-card p-4 border-0">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom border-light border-opacity-10">
                <div class="bg-primary bg-opacity-25 rounded-circle p-3 me-3">
                    <i class="bi bi-pie-chart-fill text-info fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-white mb-0">Dashboard Pemantauan Panitia</h3>
                    <p class="text-muted mb-0">Statistik *Real-Time* Partisipasi Pemilih.</p>
                </div>
                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('panitia.kandidat.index') }}" class="btn btn-outline-info fw-bold rounded-3">Kelola Kandidat</a>
                    <a href="{{ route('panitia.dpt.index') }}" class="btn btn-outline-primary fw-bold rounded-3">Kelola DPT</a>
                    <a href="{{ route('panitia.agenda.index') }}" class="btn btn-outline-warning fw-bold rounded-3">Kelola Agenda</a>
                </div>
            </div>
            
            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="card bg-primary bg-opacity-10 border-0 h-100 p-4 rounded-4 position-relative overflow-hidden" style="border: 1px solid rgba(56,189,248,0.2) !important;">
                        <i class="bi bi-people-fill position-absolute text-primary opacity-25" style="font-size: 8rem; right: -20px; bottom: -30px;"></i>
                        <h6 class="text-info fw-bold text-uppercase tracking-wider mb-2">Total DPT</h6>
                        <h1 class="display-3 fw-bold text-white mb-0">{{ number_format($totalPemilih) }}</h1>
                        <p class="text-muted small mt-2">Daftar Pemilih Tetap</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-success bg-opacity-10 border-0 h-100 p-4 rounded-4 position-relative overflow-hidden" style="border: 1px solid rgba(25,135,84,0.2) !important;">
                        <i class="bi bi-check-circle-fill position-absolute text-success opacity-25" style="font-size: 8rem; right: -20px; bottom: -30px;"></i>
                        <h6 class="text-success fw-bold text-uppercase tracking-wider mb-2">Partisipasi Aktif</h6>
                        <h1 class="display-3 fw-bold text-white mb-0">{{ number_format($sudahMemilih) }}</h1>
                        <p class="text-success text-opacity-75 small mt-2">Suara Berhasil Masuk</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-warning bg-opacity-10 border-0 h-100 p-4 rounded-4 position-relative overflow-hidden" style="border: 1px solid rgba(255,193,7,0.2) !important;">
                        <i class="bi bi-hourglass-split position-absolute text-warning opacity-25" style="font-size: 8rem; right: -20px; bottom: -30px;"></i>
                        <h6 class="text-warning fw-bold text-uppercase tracking-wider mb-2">Belum Partisipasi</h6>
                        <h1 class="display-3 fw-bold text-white mb-0">{{ number_format($belumMemilih) }}</h1>
                        <p class="text-warning text-opacity-75 small mt-2">Menunggu Pemilih</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-5 p-4 bg-dark bg-opacity-50 rounded-4" style="border: 1px solid rgba(255,255,255,0.05);">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle text-info fs-4 me-3"></i>
                    <div>
                        <h6 class="text-white fw-bold mb-1">Integritas Data Terjamin</h6>
                        <p class="text-muted small mb-0">Total pemilih ({{ $totalPemilih }}) selalu sama dengan jumlah yang sudah memilih ({{ $sudahMemilih }}) ditambah dengan yang belum memilih ({{ $belumMemilih }}).</p>
                    </div>
                </div>
            </div>

            <!-- List Panitia -->
            <div class="mt-5">
                <h5 class="text-white fw-bold mb-3"><i class="bi bi-person-badge"></i> Daftar Rekan Panitia (KPU)</h5>
                <div class="card glass-card p-4 border-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="py-3 text-muted fw-bold small text-uppercase">Nama</th>
                                    <th class="py-3 text-muted fw-bold small text-uppercase">Email</th>
                                    <th class="py-3 text-muted fw-bold small text-uppercase">NIM/ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($panitias as $pan)
                                <tr>
                                    <td class="text-white fw-bold">{{ $pan->name }}</td>
                                    <td class="text-muted">{{ $pan->email }}</td>
                                    <td class="text-info">{{ $pan->nim }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
