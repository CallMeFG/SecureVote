@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 text-center mb-4">
        <h2 class="fw-bold">Daftar Kandidat (Himpunan K)</h2>
    </div>
</div>

<div class="row justify-content-center">
    @forelse($candidates as $candidate)
        <div class="col-md-4 mb-4">
            <div class="card glass-card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary">{{ $candidate->name }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">NIM: {{ $candidate->nim }}</h6>
                    <hr>
                    <strong>Visi:</strong>
                    <p class="card-text small">{{ $candidate->vision }}</p>
                    <strong>Misi:</strong>
                    <p class="card-text small">{{ $candidate->mission }}</p>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center">
            <p class="text-muted">Belum ada data kandidat.</p>
        </div>
    @endforelse
</div>
@endsection
