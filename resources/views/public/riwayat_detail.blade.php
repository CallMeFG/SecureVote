@extends('layouts.app')

@section('content')
<div class="row mb-5">
    <div class="col-12">
        <a href="{{ route('public.riwayat') }}" class="btn btn-outline-secondary btn-sm mb-4 rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <div class="text-center">
            <span class="badge bg-primary bg-opacity-10 text-info px-3 py-2 rounded-pill mb-3 border border-info border-opacity-25">
                <i class="bi bi-archive me-1"></i> ARSIP RESMI
            </span>
            <h2 class="fw-bold text-white">Hasil Periode {{ $period->period_name }}</h2>
            <p class="text-muted">Data didekripsi secara otomatis dari basis data sejarah.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card glass-card p-4 border-0">
            <div class="table-responsive">
                <table class="table table-hover table-borderless align-middle mb-0">
                    <thead class="border-bottom border-light border-opacity-10">
                        <tr>
                            <th class="text-muted text-uppercase small fw-bold pb-3 ps-3">Paslon</th>
                            <th class="text-muted text-uppercase small fw-bold pb-3 text-end pe-4">Perolehan Suara</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($results as $res)
                            <tr class="border-bottom border-light border-opacity-10 {{ $loop->first ? 'bg-success bg-opacity-10' : '' }}">
                                <td class="py-4 ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary bg-opacity-25 text-light fs-5 fw-bold d-flex align-items-center justify-content-center rounded me-4 position-relative" style="width: 50px; height: 50px;">
                                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                            @if($loop->first)
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark border border-warning" style="font-size: 0.6rem;">
                                                    <i class="bi bi-star-fill"></i> WIN
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="fw-bold text-white mb-1">
                                                {{ $res['candidate']->name }} <span class="text-muted fw-normal fs-6">&</span> {{ $res['candidate']->vice_name ?? '-' }}
                                            </h5>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end py-4 pe-4">
                                    <h1 class="fw-bold {{ $loop->first ? 'text-success' : 'text-white' }} mb-0">{{ number_format($res['votes']) }}</h1>
                                    <span class="{{ $loop->first ? 'text-success opacity-75' : 'text-muted' }} small">suara sah</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-5 text-muted">
                                    Data rekapitulasi belum tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
