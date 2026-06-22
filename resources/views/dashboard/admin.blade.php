@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card glass-card p-4 border-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom border-light border-opacity-10 gap-3">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="bi bi-sliders text-danger fs-3"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold text-white mb-0">Konsol Admin Sistem</h3>
                        <p class="text-muted mb-0">Manajemen Akses & Pemantauan Keamanan Terpusat</p>
                    </div>
                </div>
                
                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('admin.periode.index') }}" class="btn btn-outline-primary fw-bold rounded-3">Manajemen Periode</a>
                    <a href="{{ route('admin.panitia.index') }}" class="btn btn-outline-info fw-bold rounded-3">Manajemen Panitia</a>
                </div>
            </div>
            
            @if($period && $period->is_active)
                <div class="alert bg-success bg-opacity-10 border border-success border-opacity-25 text-success d-flex align-items-center rounded-3">
                    <div class="spinner-grow spinner-grow-sm me-3 text-success" role="status"></div>
                    <div><strong>Sistem Aktif:</strong> Portal pemilihan sedang menerima suara dari pemilih yang terverifikasi.</div>
                </div>
            @else
                <div class="alert bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-light d-flex align-items-center rounded-3">
                    <i class="bi bi-shield-lock-fill me-3 fs-5"></i>
                    <div><strong>Sistem Terkunci:</strong> Portal pemilihan saat ini ditutup untuk publik.</div>
                </div>
            @endif

            <h5 class="mt-5 mb-4 text-white fw-bold"><i class="bi bi-activity text-info me-2"></i> Log Sistem Keamanan</h5>
            <div class="card bg-dark bg-opacity-50 border-0 rounded-4 overflow-hidden" style="border: 1px solid rgba(255,255,255,0.05) !important;">
                <div class="table-responsive">
                    <table class="table table-hover table-dark table-borderless align-middle mb-0">
                        <thead class="border-bottom border-light border-opacity-10">
                            <tr>
                                <th class="py-3 text-muted fw-bold small text-uppercase ps-4">Waktu</th>
                                <th class="py-3 text-muted fw-bold small text-uppercase">Aktor / NIM</th>
                                <th class="py-3 text-muted fw-bold small text-uppercase">Nama Aktor</th>
                                <th class="py-3 text-muted fw-bold small text-uppercase">Jenis Kejadian</th>
                                <th class="py-3 text-muted fw-bold small text-uppercase pe-4">Keterangan Teknis</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr class="border-bottom border-light border-opacity-5">
                                <td class="py-3 ps-4 text-muted small">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td class="py-3 text-light fw-bold">{{ $log->user ? $log->user->nim : 'Sistem' }}</td>
                                <td class="py-3 text-light">{{ $log->user ? $log->user->name : '-' }}</td>
                                <td class="py-3">
                                    @php
                                        $badgeColor = match($log->action) {
                                            'login_attempt' => 'info',
                                            'vote_cast' => 'success',
                                            'access_denied' => 'danger',
                                            'otp_verify' => 'primary',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} border border-{{ $badgeColor }} border-opacity-25 px-2 py-1">
                                        {{ str_replace('_', ' ', strtoupper($log->action)) }}
                                    </span>
                                </td>
                                <td class="py-3 pe-4 text-muted small">{{ $log->description }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada log terekam dalam sistem.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
