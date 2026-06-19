@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card glass-card p-5 text-center">
            <div class="d-inline-block bg-primary bg-opacity-10 p-3 rounded-circle mb-3">
                <i class="bi bi-shield-check fs-1 text-gradient"></i>
            </div>
            <h3 class="fw-bold">Verifikasi Identitas</h3>
            <p class="text-muted">Masukkan 6 digit kode keamanan yang telah diberikan kepada Anda.</p>
            
            @if(session('debug_otp'))
                <div class="alert alert-info bg-info bg-opacity-10 border-info border-opacity-25 mt-3 mb-4 text-info">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Sistem mendeteksi mode simulasi. Kode Anda: <strong class="fs-5 ms-1">{{ session('debug_otp') }}</strong>
                </div>
            @endif

            <form action="{{ route('otp.verify') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-4">
                    <input type="text" name="token" class="form-control form-control-lg text-center fs-3 tracking-widest bg-dark bg-opacity-25 text-white @error('token') is-invalid @enderror" placeholder="• • • • • •" maxlength="6" autocomplete="off" required style="letter-spacing: 0.5em;">
                    @error('token')
                        <div class="invalid-feedback mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mt-2">Selesaikan Verifikasi</button>
            </form>
        </div>
    </div>
</div>
@endsection
