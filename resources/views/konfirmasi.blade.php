@extends('layouts.admin')

@section('main-content')
<div class="container mt-5">
    <div class="card shadow-sm border-0 p-4 rounded-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Konfirmasi Setoran</h2>
        </div>
        <form action="{{ route('final.konfirmasi') }}" method="POST">
            @csrf
            <div class="row g-3">
                @php
                    $items = [
                        'botol_plastik' => 'Botol Plastik',
                        'kaleng' => 'Kaleng',
                        'botol_kaca' => 'Botol Kaca',
                    ];
                @endphp
                @foreach ($items as $key => $label)
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jumlah {{ $label }}</label>
                        <input type="text" class="form-control" value="{{ $data['jumlah_' . $key] ?? 0 }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Harga {{ $label }}</label>
                        <input type="text" class="form-control" value="Rp. {{ number_format($data['harga_' . $key] ?? 0, 0, ',', '.') }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Harga Total {{ $label }}</label>
                        <input type="text" class="form-control" value="Rp. {{ number_format(($data['jumlah_' . $key] ?? 0) * ($data['harga_' . $key] ?? 0), 0, ',', '.') }}" readonly>
                    </div>
                @endforeach
                {{-- Total Keseluruhan --}}
                <div class="col-md-12">
                    <label class="form-label fw-bold">Total</label>
                    <input type="text" class="form-control text-success fw-bold fs-5" value="Rp. {{ number_format($data['total'] ?? 0, 0, ',', '.') }}" readonly>
                    <input type="hidden" name="total" value="{{ $data['total'] ?? 0 }}">
                </div>
            </div>
            <div class="text-center mt-5">
                <button type="submit" class="btn btn-primary px-5">OK</button>
            </div>
        </form>
    </div>
</div>
@endsection
