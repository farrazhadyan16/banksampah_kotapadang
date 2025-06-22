@extends('layouts.admin')
@section('main-content')
<div class="container">
    <h1 class="h3 mb-4 text-gray-800">Tarik Saldo</h1>
    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    {{-- Pesan Gagal --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    {{-- Validasi Input --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('tarik.store') }}" method="POST">
                @csrf
                {{-- Saldo Saat Ini --}}
                <div class="form-group">
                    <label>Saldo Anda Saat Ini</label>
                    <input type="text" class="form-control" value="Rp {{ number_format(Auth::user()->saldo, 0, ',', '.') }}" readonly>
                </div>
                {{-- Input Penarikan --}}
                <div class="form-group">
                    <label>Jumlah Penarikan (Minimal Rp 1.000)</label>
                    <input type="number" name="jumlah" class="form-control" required min="1000" placeholder="Masukkan jumlah penarikan">
                </div>
                <button type="submit" class="btn btn-primary">Tarik Saldo</button>
            </form>
        </div>
    </div>
</div>
@endsection
