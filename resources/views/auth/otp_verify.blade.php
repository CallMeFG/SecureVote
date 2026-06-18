@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card glass-card mt-5 p-4 text-center">
            <h4 class="fw-bold text-primary">Verifikasi OTP</h4>
            <p class="text-muted">Proposisi s (OTP valid)</p>
            
            @if(session('debug_otp'))
                <div class="alert alert-info">
                    <strong>[DEBUG SIMULASI]</strong> Kode OTP Anda: <span class="fs-4 fw-bold">{{ session('debug_otp') }}</span>
                </div>
            @endif

            <form action="{{ route('otp.verify') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="text" name="token" class="form-control form-control-lg text-center letter-spacing-3 @error('token') is-invalid @enderror" placeholder="123456" maxlength="6" required>
                    @error('token')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold">Verifikasi</button>
            </form>
        </div>
    </div>
</div>
@endsection
