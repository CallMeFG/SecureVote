@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card glass-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="text-primary fw-bold">Dashboard Admin</h3>
                
                <form action="{{ route('admin.periode.toggle') }}" method="POST">
                    @csrf
                    @if($period && $period->is_active)
                        <button class="btn btn-danger fw-bold shadow-sm">Tutup Periode Voting (t = FALSE)</button>
                    @else
                        <button class="btn btn-success fw-bold shadow-sm">Buka Periode Voting (t = TRUE)</button>
                    @endif
                </form>
            </div>
            
            <div class="alert {{ ($period && $period->is_active) ? 'alert-success' : 'alert-secondary' }} shadow-sm">
                Status Proposisi t (Periode Aktif): <strong>{{ ($period && $period->is_active) ? 'TRUE' : 'FALSE' }}</strong>
            </div>

            <hr>
            
            <h5 class="mt-4">Log Aktivitas (Graf Kejadian)</h5>
            <div class="table-responsive mt-3">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Waktu</th>
                            <th>Aktor (NIM)</th>
                            <th>Action</th>
                            <th>Status Logika (Proposisi)</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $log->user ? $log->user->nim : 'Anonim' }}</td>
                            <td><span class="badge bg-primary">{{ $log->action }}</span></td>
                            <td class="font-monospace text-muted">{{ $log->proposition_state }}</td>
                            <td>{{ $log->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
