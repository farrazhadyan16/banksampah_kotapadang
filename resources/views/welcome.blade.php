@extends('layouts.app')

@section('main-content')
<div class="w-100 bg-white text-dark">

    <!-- Hero Section -->
    <section class="min-vh-100 d-flex align-items-center bg-primary text-white">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">Bank Sampah Kota Padang</h1>
            <p class="lead mb-4">Digitalisasi pengelolaan sampah demi lingkungan yang bersih dan berdaya ekonomi.</p>
            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 shadow">Masuk Sekarang</a>
        </div>
    </section>

    <!-- Tentang -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('img/ilust.png') }}" alt="Ilustrasi Bank Sampah" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <h2 class="fw-bold text-primary mb-3">Mengapa Bank Sampah?</h2>
                    <p class="fs-5">
                        Bank Sampah Kota Padang hadir untuk memudahkan masyarakat dalam menyetorkan sampah dan mendapatkan nilai ekonomi dari sampah yang terkumpul.
                    </p>
                    <p class="fs-5 mt-4">
                        ✔️ Pantau saldo hasil setoran sampah<br>
                        ✔️ Transparansi riwayat transaksi<br>
                        ✔️ Sistem digital yang mudah diakses<br>
                        ✔️ Kontribusi nyata terhadap lingkungan
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Fitur -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="fw-bold text-primary mb-5">Fitur Layanan</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-4 border rounded h-100 shadow-sm">
                        <i class="fas fa-trash-restore fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold">Setor Sampah</h5>
                        <p>Sampaikan sampahmu ke Bank Sampah dan simpan nilainya di akunmu secara otomatis.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border rounded h-100 shadow-sm">
                        <i class="fas fa-history fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold">Riwayat Transaksi</h5>
                        <p>Lihat dan lacak semua transaksi setoran maupun penarikan saldo dengan mudah.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 border rounded h-100 shadow-sm">
                        <i class="fas fa-hand-holding-usd fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold">Tarik Saldo</h5>
                        <p>Tarik saldo hasil sampah yang telah dikonversi secara langsung dan transparan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ajak Bergabung -->
    <section class="py-5 bg-primary text-white text-center">
        <div class="container">
            <h3 class="fw-bold mb-3">Gabung Sekarang dan Jadikan Sampahmu Lebih Bernilai!</h3>
            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4">Masuk / Daftar</a>
        </div>
    </section>

</div>
@endsection
