@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center">
        <h2 class="fw-bold">Pilih Kandidat Anda</h2>
        <p class="text-muted">Proposisi p ∧ q ∧ r ∧ s ∧ ¬v ∧ t bernilai TRUE. Hak pilih aktif.</p>
    </div>
</div>

<div class="row justify-content-center">
    @forelse($candidates as $candidate)
        <div class="col-md-4 mb-4">
            <div class="card glass-card h-100 shadow-sm transition-hover">
                <div class="card-body text-center">
                    <div class="display-1 text-primary mb-3">{{ $candidate->id }}</div>
                    <h4 class="card-title fw-bold">{{ $candidate->name }}</h4>
                    <p class="card-text text-muted">{{ $candidate->vision }}</p>
                    <hr>
                    <form action="{{ route('voting.submit') }}" method="POST" onsubmit="return confirm('Yakin dengan pilihan Anda? Suara yang masuk tidak dapat diubah (Status v akan menjadi TRUE).')">
                        @csrf
                        <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                        <button type="submit" class="btn btn-success w-100 fw-bold">PILIH KANDIDAT {{ $candidate->id }}</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center">
            <p class="lead">Belum ada kandidat yang terdaftar.</p>
        </div>
    @endforelse
</div>
@endsection
