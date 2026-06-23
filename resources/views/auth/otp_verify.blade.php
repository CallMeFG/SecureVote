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
            
            @if(session('success'))
                <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 mt-3 mb-2 text-success text-start">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 mt-3 mb-2 text-danger text-start">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                </div>
            @endif
            
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

            <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 text-center">
                <form action="{{ route('otp.resend') }}" method="POST" id="resendForm">
                    @csrf
                    <p class="text-muted mb-2 fs-6">Tidak menerima email OTP?</p>
                    <button type="submit" id="btnResend" class="btn btn-outline-secondary btn-sm px-4" disabled>
                        Kirim Ulang OTP (<span id="countdown">30</span>s)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let btnResend = document.getElementById('btnResend');
        let countdownSpan = document.getElementById('countdown');
        // Retrieve last sent time from session if possible, or just start from 30
        @php
            $lastSent = session('otp_last_sent_at') ? (int) session('otp_last_sent_at') : time() - 30;
            $elapsed = time() - $lastSent;
            $timeLeft = max(0, 30 - $elapsed);
            if ($timeLeft > 30) $timeLeft = 30; // Mencegah timer lebih dari 30 detik
        @endphp
        let timeLeft = {{ $timeLeft }};
        
        if (timeLeft <= 0) {
            btnResend.disabled = false;
            btnResend.innerHTML = 'Kirim Ulang OTP';
            btnResend.classList.remove('btn-outline-secondary');
            btnResend.classList.add('btn-outline-primary');
        } else {
            let timerId = setInterval(function() {
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(timerId);
                    btnResend.disabled = false;
                    btnResend.innerHTML = 'Kirim Ulang OTP';
                    btnResend.classList.remove('btn-outline-secondary');
                    btnResend.classList.add('btn-outline-primary');
                } else {
                    countdownSpan.innerText = timeLeft;
                }
            }, 1000);
        }
    });
</script>
@endsection
