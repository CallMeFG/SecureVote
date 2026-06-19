@extends('layouts.app')

@section('content')
<div class="row mb-5">
    <div class="col-12 text-center">
        <h2 class="fw-bold text-white">Rekapitulasi Suara</h2>
        <p class="text-muted">Hasil perhitungan suara otomatis menggunakan dekripsi terkini.</p>
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
                            <tr class="border-bottom border-light border-opacity-10">
                                <td class="py-4 ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-info fs-5 fw-bold d-flex align-items-center justify-content-center rounded me-4" style="width: 50px; height: 50px;">
                                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                        </div>
                                        <div>
                                            <h5 class="fw-bold text-white mb-1">{{ $res['candidate']->name }} <span class="text-muted fw-normal fs-6">&</span> {{ $res['candidate']->vice_name ?? '-' }}</h5>
                                            <small class="text-muted d-flex gap-3">
                                                <span>Gubernur: {{ $res['candidate']->nim }}</span>
                                                <span>Wakil: {{ $res['candidate']->vice_nim ?? '-' }}</span>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end py-4 pe-4">
                                    <h1 class="fw-bold text-gradient mb-0">{{ number_format($res['votes']) }}</h1>
                                    <span class="text-muted small">suara sah</span>
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
            @if(count($results) > 0)
                <div class="mt-4 pt-3 border-top border-light border-opacity-10 text-center">
                    <p class="small text-muted mb-0"><i class="bi bi-shield-check me-1 text-success"></i> Seluruh suara telah diverifikasi dan bebas dari duplikasi.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
