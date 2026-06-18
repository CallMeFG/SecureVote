@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 text-center mb-4">
        <h2 class="fw-bold text-primary">Hasil Pemilihan Sementara</h2>
        <p class="text-muted">Proses dekripsi *batch* menggunakan kunci AES-256-CBC</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card glass-card p-4 shadow-sm">
            <table class="table table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID Kandidat</th>
                        <th>Nama</th>
                        <th>Total Suara</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $res)
                        <tr>
                            <td>{{ $res['candidate']->id }}</td>
                            <td class="fw-bold">{{ $res['candidate']->name }}</td>
                            <td><h2>{{ $res['votes'] }}</h2></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
