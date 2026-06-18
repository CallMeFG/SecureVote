@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 text-center mt-5">
        <h1 class="display-4 fw-bold text-primary">Selamat Datang di SecureVote</h1>
        <p class="lead">Sistem E-Voting HIMA Teknik Informatika PCR</p>
        <p class="text-muted">Pemilihan berlandaskan Logika Matematika dan Teori Himpunan.</p>
        
        <div class="mt-4">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 shadow-sm rounded-pill">Mulai Voting</a>
        </div>
    </div>
</div>
@endsection
