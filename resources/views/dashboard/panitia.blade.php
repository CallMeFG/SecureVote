@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card glass-card p-4">
            <h3 class="text-primary fw-bold">Dashboard Panitia KPU</h3>
            <p>Pemantauan *Real-time* Teori Himpunan Partisi</p>
            <hr>
            <div class="row text-center mt-4">
                <div class="col-md-4">
                    <div class="card bg-light p-4 shadow-sm border-0">
                        <h6 class="text-muted">Total DPT (|S|)</h6>
                        <h1 class="display-3 fw-bold text-dark">{{ $totalPemilih }}</h1>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white p-4 shadow-sm border-0">
                        <h6>Sudah Memilih (|V_h|)</h6>
                        <h1 class="display-3 fw-bold">{{ $sudahMemilih }}</h1>
                        <small>v = TRUE</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white p-4 shadow-sm border-0">
                        <h6>Belum Memilih (|V_b|)</h6>
                        <h1 class="display-3 fw-bold">{{ $belumMemilih }}</h1>
                        <small>v = FALSE</small>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-4">
                <strong>Pembuktian Teori Himpunan:</strong> DPT = V_h ∪ V_b ⟶ {{ $totalPemilih }} = {{ $sudahMemilih }} + {{ $belumMemilih }} (Partisi Valid).
            </div>
        </div>
    </div>
</div>
@endsection
