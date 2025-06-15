@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">Hasil Pencarian: "{{ $keyword }}"</h1>

    <h5>Data Nasabah:</h5>
    <ul>
        @forelse ($nasabah as $n)
            <li>{{ $n->name }} ({{ $n->email }})</li>
        @empty
            <li>Tidak ditemukan.</li>
        @endforelse
    </ul>

    <h5>Data Sampah:</h5>
    <ul>
        @forelse ($sampah as $s)
            <li>{{ $s->jenis_sampah }}</li>
        @empty
            <li>Tidak ditemukan.</li>
        @endforelse
    </ul>

    <h5>Riwayat Transaksi:</h5>
    <ul>
        @forelse ($riwayat as $r)
            <li>{{ strtoupper($r->jenis_transaksi) }} oleh {{ $r->nasabah->name ?? '-' }} ({{ $r->created_at->format('d-m-Y') }})</li>
        @empty
            <li>Tidak ditemukan.</li>
        @endforelse
    </ul>
@endsection
