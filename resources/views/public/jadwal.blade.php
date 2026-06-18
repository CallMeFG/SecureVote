@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 text-center mt-5">
        <h2 class="fw-bold text-primary">Jadwal Pemilihan</h2>
        <div class="card glass-card p-5 mt-4 shadow-sm">
            @if($period)
                @if($period->is_active)
                    <h1 class="text-success fw-bold">SEDANG BERLANGSUNG</h1>
                    <p class="lead">Periode voting sedang aktif (Proposisi t = TRUE).</p>
                    <p>Silakan login dan gunakan hak pilih Anda.</p>
                @else
                    <h1 class="text-danger fw-bold">DITUTUP</h1>
                    <p class="lead">Periode voting ditutup (Proposisi t = FALSE).</p>
                    <p>Akses ke halaman voting ditolak secara logika.</p>
                @endif
            @else
                <h1 class="text-secondary fw-bold">BELUM DITENTUKAN</h1>
                <p>Panitia belum menetapkan jadwal pemilihan.</p>
            @endif
        </div>
    </div>
</div>
@endsection
