@extends('layouts.admin')

@section('main-content')
<div class="container mt-4">
    <form method="POST" action="{{ route('setor.konfirmasi') }}">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center">
                <h4 class="mb-4">Setor Sampah - Deteksi Otomatis Kamera</h4>

                <div style="position: relative; width: 100%; max-width: 600px; margin: auto;">
                    <video id="camera" autoplay playsinline muted style="width: 100%; border-radius: 8px;"></video>
                    <canvas id="overlay" style="position: absolute; top: 0; left: 0; width: 100%;"></canvas>
                </div>

                <p id="detectedLabel" class="mt-3 text-success fw-bold"></p>
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
const canvas = document.getElementById('overlay');
const ctx = canvas.getContext('2d');

// Start Kamera
async function startCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({
    video: {
        width: { ideal: 640 },
        height: { ideal: 480 },
        facingMode: "environment"  // opsional: untuk pakai kamera belakang di HP
    }
});

        video.srcObject = stream;

        video.onloadedmetadata = () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Mulai deteksi berkala
            setInterval(sendFrameToFlask, 1500); // Kirim tiap 1.5 detik
        };
    } catch (err) {
        alert('Gagal mengakses kamera: ' + err.message);
    }
}

async function sendFrameToFlask() {
    // Ambil frame
    const tmpCanvas = document.createElement('canvas');
    tmpCanvas.width = video.videoWidth;
    tmpCanvas.height = video.videoHeight;
    tmpCanvas.getContext('2d').drawImage(video, 0, 0);
    const imageBase64 = tmpCanvas.toDataURL('image/jpeg').split(',')[1];

    try {
        const response = await fetch('http://127.0.0.1:5000/predict', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ image: imageBase64 }),
        });

        const result = await response.json();

        // Tampilkan bounding box image ke canvas overlay
        if (result.image_with_boxes) {
            const image = new Image();
            image.onload = () => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
            };
            image.src = "data:image/jpeg;base64," + result.image_with_boxes;
        }

        // Tampilkan hasil deteksi teks
        if (result.detected && result.detected.length > 0) {
            document.getElementById('detectedLabel').innerText = "Terdeteksi: " + result.detected.join(', ');

            // Isi otomatis input berdasarkan deteksi
            result.detected.forEach(item => {
                const name = item.toLowerCase().replace(/\s+/g, '_');
                const input = document.querySelector(`input[name='jumlah_${name}']`);
                if (input) input.value = 1;
            });
        } else {
            document.getElementById('detectedLabel').innerText = "Tidak ada sampah terdeteksi.";
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

    } catch (err) {
        console.error('Error saat mengirim ke Flask:', err);
    }
}

startCamera();
</script>
@endsection
