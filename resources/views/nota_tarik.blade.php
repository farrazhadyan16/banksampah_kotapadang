@extends('layouts.admin')

@section('main-content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Nota Penarikan Saldo</h4>
        </div>
        <div class="card-body">
            <p><strong>ID Riwayat:</strong> {{ $tarik->id_riwayat }}</p>
            <p><strong>Nama:</strong> {{ $tarik->user->name }}</p>
            <p><strong>Email:</strong> {{ $tarik->user->email }}</p>
            <p><strong>No HP:</strong> {{ $tarik->user->no_hp }}</p>
            <p><strong>Jumlah Penarikan:</strong> Rp {{ number_format($tarik->jumlah, 0, ',', '.') }}</p>
            <p><strong>Tanggal:</strong> {{ $tarik->created_at->format('d-m-Y H:i') }}</p>

            <a href="{{ route('tarik.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>
@endsection
