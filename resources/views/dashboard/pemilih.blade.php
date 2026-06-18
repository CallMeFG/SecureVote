@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card glass-card p-4">
            <h3>Dashboard Pemilih</h3>
            <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->nim }})</p>
            <hr>
            
            <div class="row text-center mt-4">
                <div class="col-md-6">
                    <div class="card bg-light p-3">
                        <h5>Status Logika Proposisi v</h5>
                        @if(auth()->user()->is_voted)
                            <h2 class="text-success fw-bold">v = TRUE</h2>
                            <p class="text-muted">(Anda sudah memilih, bagian dari partisi V_h)</p>
                            <button class="btn btn-secondary disabled mt-2">Mulai Voting</button>
                            <p class="text-danger small mt-2">Akses ke VotingPage terkunci (¬v = FALSE)</p>
                        @else
                            <h2 class="text-danger fw-bold">v = FALSE</h2>
                            <p class="text-muted">(Anda belum memilih, bagian dari partisi V_b)</p>
                            <a href="{{ route('voting.index') }}" class="btn btn-primary mt-2 fw-bold">Mulai Voting</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light p-3">
                        <h5>Riwayat Aktivitas</h5>
                        <ul class="list-group text-start mt-2">
                            @foreach(auth()->user()->activityLogs()->latest()->take(3)->get() as $log)
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-bold">{{ $log->action }}</div>
                                        <small>{{ $log->description }}</small><br>
                                        <span class="badge bg-dark">{{ $log->proposition_state }}</span>
                                    </div>
                                    <span class="badge bg-secondary rounded-pill">{{ $log->created_at->diffForHumans() }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
