@extends('layouts.admin')

@section('main-content')
<div class="container mt-4">
    <form method="POST" action="{{ route('setor.konfirmasi') }}">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center">
                <h4 class="mb-4">Setoran Sampah - Deteksi Otomatis Kamera</h4>

                <div style="position: relative; width: 100%; max-width: 600px; margin: auto;">
                    <video id="camera" autoplay playsinline muted style="width: 100%; border-radius: 8px; transform: scaleX(-1);"></video>

                    <div class="text-center mt-3">
                        <button type="button" id="captureBtn" class="btn btn-secondary">Ambil Gambar</button>
                    </div>
                </div>

                <p id="detectedLabel" class="mt-3 text-success fw-bold"></p>

                <div class="mt-4" id="capturedImages"></div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Form Setoran Sampah</h5>

                @php
                    $sampah = ['Botol Plastik', 'Kaleng', 'Ban Karet', 'Botol Kaca', 'Galon'];
                @endphp

                @foreach ($sampah as $item)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Jumlah {{ $item }}</label>
                        <input type="number" name="jumlah_{{ strtolower(str_replace(' ', '_', $item)) }}" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-4">
                        <label>Harga {{ $item }}</label>
                        <input type="text" class="form-control" value="Rp. 1000" readonly>
                    </div>
                </div>
                @endforeach

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary ms-2">Konfirmasi Setor</button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- JavaScript --}}
<script>
const video = document.getElementById('camera');
let capturedImages = [];
let detectedCounter = {};

// Mulai Kamera
async function startCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: {
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: "environment"
            }
        });
        video.srcObject = stream;
    } catch (err) {
        alert('Gagal mengakses kamera: ' + err.message);
    }
}

startCamera();

document.getElementById('captureBtn').addEventListener('click', async () => {
    const tmpCanvas = document.createElement('canvas');
    tmpCanvas.width = video.videoWidth;
    tmpCanvas.height = video.videoHeight;
    const tmpCtx = tmpCanvas.getContext('2d');

    tmpCtx.translate(tmpCanvas.width, 0);
    tmpCtx.scale(-1, 1);
    tmpCtx.drawImage(video, 0, 0, tmpCanvas.width, tmpCanvas.height);

    const imageBase64 = tmpCanvas.toDataURL('image/jpeg').split(',')[1];

    try {
        const response = await fetch('http://127.0.0.1:5000/predict', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ image: imageBase64 }),
        });

        const result = await response.json();

        if (result.image_with_boxes) {
            const rawImg = new Image();
            rawImg.onload = () => {
                const mirrorCanvas = document.createElement('canvas');
                mirrorCanvas.width = rawImg.width;
                mirrorCanvas.height = rawImg.height;
                const ctx = mirrorCanvas.getContext('2d');

                ctx.translate(mirrorCanvas.width, 0);
                ctx.scale(-1, 1);
                ctx.drawImage(rawImg, 0, 0);

                const mirroredBase64 = mirrorCanvas.toDataURL('image/jpeg');
                capturedImages.unshift({
                    src: mirroredBase64,
                    detected: result.detected.map(item => item.toLowerCase().replace(/\s+/g, '_'))
                });

                renderCapturedImages();
            };
            rawImg.src = "data:image/jpeg;base64," + result.image_with_boxes;
        }

if (result.detected && result.detected.length > 0) {
    document.getElementById('detectedLabel').innerText = "Terdeteksi: " + result.detected.join(', ');

    const countMap = result.detected.reduce((acc, item) => {
        const key = item.toLowerCase().replace(/\s+/g, '_');
        acc[key] = (acc[key] || 0) + 1;
        return acc;
    }, {});

    // Perbarui nilai input dan counter
    Object.entries(countMap).forEach(([name, count]) => {
        detectedCounter[name] = (detectedCounter[name] || 0) + count;

        const input = document.querySelector(`input[name='jumlah_${name}']`);
        if (input) {
            const current = parseInt(input.value || '0');
            input.value = current + count;
        }
    });
} else {
    document.getElementById('detectedLabel').innerText = "Tidak ada sampah terdeteksi.";
}


    } catch (err) {
        console.error('Gagal kirim ke Flask:', err);
    }
});

function renderCapturedImages() {
    const container = document.getElementById('capturedImages');
    container.innerHTML = '';

    capturedImages.forEach((imageData, index) => {
        const wrapper = document.createElement('div');
        wrapper.className = 'position-relative mb-3';

        const img = new Image();
        img.src = imageData.src;
        img.className = 'img-fluid rounded shadow-sm';
        img.style.maxWidth = '600px';

        const deleteBtn = document.createElement('button');
        deleteBtn.className = 'btn btn-danger btn-sm position-absolute';
        deleteBtn.innerText = 'Hapus';
        deleteBtn.style.top = '10px';
        deleteBtn.style.right = '10px';
        deleteBtn.onclick = () => {
            imageData.detected.forEach(item => {
                const input = document.querySelector(`input[name='jumlah_${item}']`);
                if (input) {
                    const current = parseInt(input.value || '0');
                    input.value = Math.max(0, current - 1);
                    detectedCounter[item] = Math.max(0, (detectedCounter[item] || 1) - 1);
                }
            });

            capturedImages.splice(index, 1);
            renderCapturedImages();
        };

        wrapper.appendChild(img);
        wrapper.appendChild(deleteBtn);
        container.appendChild(wrapper);
    });
}
</script>


@endsection
