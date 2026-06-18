@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card glass-card mt-5 p-4">
            <h3 class="text-center mb-4 fw-bold text-primary">Login SecureVote</h3>
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">NIM / Username</label>
                    <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" required>
                    @error('nim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Proposisi p (NIM valid)</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Proposisi q (Password benar)</small>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary fw-bold">Login & Kirim OTP</button>
                    <button type="submit" name="tamu" value="1" class="btn btn-outline-secondary">Masuk sebagai Tamu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
