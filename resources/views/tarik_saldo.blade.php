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
                {{-- Tujuan Bank --}}
                <div class="form-group">
                    <label>Nama Bank</label>
                    <select name="nama_bank" class="form-control" required>
                        <option value="">-- Pilih Bank --</option>
                        <option value="BCA">BCA (Bank Central Asia)</option>
                        <option value="BRI">BRI (Bank Rakyat Indonesia)</option>
                        <option value="BNI">BNI (Bank Negara Indonesia)</option>
                        <option value="Mandiri">Mandiri</option>
                        <option value="BTN">BTN (Bank Tabungan Negara)</option>
                        <option value="CIMB Niaga">CIMB Niaga</option>
                        <option value="Danamon">Danamon</option>
                        <option value="Permata">Permata Bank</option>
                        <option value="Bank Syariah Indonesia">Bank Syariah Indonesia (BSI)</option>
                        <option value="Maybank">Maybank</option>
                        <option value="OCBC NISP">OCBC NISP</option>
                        <option value="Mega">Bank Mega</option>
                        <option value="Panin">Bank Panin</option>
                        <option value="BTPN">BTPN</option>
                        <option value="Jago">Bank Jago</option>
                        <option value="SeaBank">SeaBank</option>
                        <option value="Bank Lainnya">Bank Lainnya</option>
                    </select>
                </div>

                {{-- Nomor Rekening --}}
                <div class="form-group">
                    <label>Nomor Rekening</label>
                    <input type="number" name="rek_bank" class="form-control" required placeholder="Masukkan nomor rekening">
                </div>

                {{-- Atas Nama --}}
                <div class="form-group">
                    <label>Atas Nama</label>
                    <input type="text" name="tujuan_bank" class="form-control" required placeholder="Masukkan nama pemilik rekening">
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
