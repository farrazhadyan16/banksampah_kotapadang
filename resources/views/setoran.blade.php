@extends('layouts.admin')

@section('main-content')
<div class="container mt-4">
    <form method="POST" action="{{ route('setor.konfirmasi') }}">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center">
                <h4 class="mb-4">Setor Sampah - Buka Kamera</h4>
                <video id="camera" autoplay playsinline class="rounded mb-3" style="width: 100%; max-width: 600px;"></video>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <button type="button" onclick="capture()" class="btn btn-secondary">Ambil Gambar</button>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Form Setor Sampah</h5>

                @php
                    $sampah = ['Botol Plastik', 'Kaleng', 'Ban Karet', 'Botol Kaca', 'Galon'];
                @endphp

                @foreach ($sampah as $item)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Jumlah {{ $item }}</label>
                        <input type="number" name="jumlah_{{ strtolower(str_replace(' ', '_', $item)) }}" class="form-control" value="0">
                    </div>
                    <div class="col-md-4">
                        <label>Harga {{ $item }}</label>
                        <input type="text" class="form-control" value="Rp. 1000" readonly>
                    </div>
                </div>
                @endforeach

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const video = document.getElementById('camera');

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
        } catch (err) {
            alert('Gagal mengakses kamera: ' + err.message);
        }
    }

    function capture() {
        alert('Kamera berjalan. Tambahkan logika simpan gambar di sini.');
    }

    startCamera();
</script>
@endsection
