@extends('layouts.admin')

@section('main-content')

@php
    $userRole = auth()->user()->role ?? '';
@endphp

@if (!in_array($userRole, ['admin', 'super_admin']))
    <div class="alert alert-danger mt-3">
        Anda tidak memiliki izin untuk mengakses halaman ini.
    </div>
@else

<h1 class="h3 mb-4 text-gray-800">Form Setor Sampah</h1>

{{-- Pesan sukses --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

{{-- Validasi error --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mt-2 mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('setorsampah.store') }}" method="POST">
            @csrf

            <div class="form-group">
                @if(Auth::user()->role != 'nasabah')
                    <label for="id_nasabah">Pilih Nasabah</label>
                    <select name="id_nasabah" required class="form-control">
    <option value="">-- Pilih Nasabah --</option>
    @foreach($listnasabah as $nasabah)
        <option value="{{ $nasabah->id }}">{{ $nasabah->name }}</option>
    @endforeach
</select>

                @endif
            </div>

            <div class="form-group">
    <label for="id_sampah">Pilih Jenis Sampah</label>
    <select name="id_sampah" id="id_sampah" class="form-control" required>
        <option value="">-- Pilih Jenis Sampah --</option>
        @foreach($listsampah as $sampah)
            <option value="{{ $sampah->id }}" data-harga="{{ $sampah->harga_satuan }}">
                {{ $sampah->jenis_sampah }} - Rp{{ number_format($sampah->harga_satuan) }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="jumlah_sampah">Jumlah (kg)</label>
    <input type="number" name="jumlah_sampah" id="jumlah_sampah" step="0.01" class="form-control" required>
</div>

<div class="form-group">
    <label for="total_harga">Total Harga (Rp)</label>
    <input type="text" name="total_harga" id="total_harga" class="form-control" readonly>
</div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Setor</button>
            <a href="{{ route('setorsampah.store') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@endif

@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jumlahInput = document.getElementById('jumlah_sampah');
        const sampahSelect = document.getElementById('id_sampah');
        const totalHargaInput = document.getElementById('total_harga');

        function updateTotalHarga() {
            const selectedOption = sampahSelect.options[sampahSelect.selectedIndex];
            const hargaSatuan = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
            const jumlah = parseFloat(jumlahInput.value) || 0;
            const total = hargaSatuan * jumlah;
            totalHargaInput.value = total.toFixed(0); // Tanpa desimal
        }

        jumlahInput.addEventListener('input', updateTotalHarga);
        sampahSelect.addEventListener('change', updateTotalHarga);
    });
</script>
