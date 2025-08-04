@extends('layouts.admin')
@section('main-content')
<div class="container mt-4">
    <form method="POST" action="{{ route('setoran.konfirmasi') }}">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center">
                <h4 class="mb-4">Setoran Sampah - Deteksi Otomatis Kamera</h4>
                <div style="position: relative; width: 100%; max-width: 600px; margin: auto;">
                    <video id="camera" autoplay playsinline muted style="width: 100%; border-radius: 8px;"></video>
                    <div class="text-center mt-3">
                        <button type="button" id="captureBtn" class="btn btn-secondary">Ambil Gambar</button>
                    </div>
                    <div class="mt-3 text-start" id="summaryDetected"></div>
                </div>
                <p id="detectedLabel" class="mt-3 text-success fw-bold"></p>
                <div class="mt-4" id="capturedImages"></div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Form Setoran Sampah</h5>
                @php
                $sampahList = [
                    'botol_plastik' => 'Botol Plastik',
                    'kaleng' => 'Kaleng',
                    'botol_kaca' => 'Botol Kaca',
                ];
                @endphp
                @foreach ($sampahList as $key => $label)
                    @php
                        $harga = $hargaSampah[$key] ?? 0;
                    @endphp
                    <div class="row mb-3 align-items-end">
                        <div class="col-md-6">
                            <label>Jumlah {{ $label }}</label>
                            <input type="number" min="0" name="jumlah_{{ $key }}" class="form-control jumlah-input" data-key="{{ $key }}" value="0" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Harga {{ $label }}/Kg</label>
                            <input type="text" class="form-control" value="Rp. {{ number_format($harga, 0, ',', '.') }}" readonly>
                            <input type="hidden" name="harga_{{ $key }}" value="{{ $harga }}">
                        </div>
                        {{-- <div class="col-md-3">
                            <label>Harga Total {{ $label }}</label>
                            <input type="text" class="form-control subtotal" id="subtotal_{{ $key }}" value="Rp. 0" readonly>
                        </div> --}}
                    </div>
                @endforeach
                {{-- <div class="row mt-4">
                    <div class="col-md-12">
                        <label class="fw-bold text-primary">Total Semua Sampah</label>
                        <input type="text" id="totalSemua" class="form-control fw-bold fs-5 text-success" value="Rp. 0" readonly>
                        <input type="hidden" name="total" id="totalHidden" value="0">
                    </div>
                </div> --}}
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
    tmpCtx.drawImage(video, 0, 0, tmpCanvas.width, tmpCanvas.height);
    const imageBase64 = tmpCanvas.toDataURL('image/jpeg').split(',')[1];
    try {
        const response = await fetch('https://farraz16-banksampah.hf.space/predict', {
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
    // document.getElementById('detectedLabel').innerText = "Terdeteksi: " + result.detected.join(', ');
    const countMap = result.detected.reduce((acc, item) => {
        const key = item.toLowerCase().replace(/\s+/g, '_');
        acc[key] = (acc[key] || 0) + 1;
        return acc;
    }, {});
    Object.entries(countMap).forEach(([name, count]) => {
        detectedCounter[name] = (detectedCounter[name] || 0) + count;
        const input = document.querySelector(`input[name='jumlah_${name}']`);
        if (input) {
            const current = parseInt(input.value || '0');
            input.value = current + count;
        }
    });
    // Update subtotal
    updateSubtotal();
    renderSummary();
    // Update daftar di bawah kamera
    const detectedList = document.getElementById('detectedList');
    detectedList.innerHTML = '';
    Object.entries(countMap).forEach(([name, count]) => {
        const label = name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        const item = document.createElement('p');
        item.className = 'mb-1 small';
        item.innerHTML = `<strong>${label}:</strong> ${count} terdeteksi`;
        detectedList.appendChild(item);
    });
} else {
    // document.getElementById('detectedLabel').innerText = "Tidak ada sampah terdeteksi.";
    document.getElementById('detectedList').innerHTML = '';
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
        wrapper.className = 'border rounded p-3 mb-3 bg-light shadow-sm';
        const img = new Image();
        img.src = imageData.src;
        img.className = 'img-fluid rounded';
        img.style.maxWidth = '100%';
        img.style.border = '3px solid #198754'; // hijau border
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
            updateSubtotal();
            renderSummary();
        };
        // Container tombol hapus
        const btnWrapper = document.createElement('div');
        btnWrapper.className = 'position-relative';
        btnWrapper.appendChild(img);
        btnWrapper.appendChild(deleteBtn);
        // List jenis sampah yang terdeteksi di gambar ini
        const summary = document.createElement('div');
        summary.className = 'mt-2 text-start';
        const countPerType = imageData.detected.reduce((acc, item) => {
            acc[item] = (acc[item] || 0) + 1;
            return acc;
        }, {});
        Object.entries(countPerType).forEach(([jenis, jumlah]) => {
            const jenisLabel = jenis.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            const p = document.createElement('p');
            p.className = 'mb-0 small';
            p.innerHTML = `<strong>${jenisLabel}:</strong> ${jumlah} terdeteksi`;
            summary.appendChild(p);
        });
        wrapper.appendChild(btnWrapper);
        wrapper.appendChild(summary);
        container.appendChild(wrapper);
    });
}
// Deteksi tombol Enter
document.addEventListener('keydown', function(event) {
    // Cek apakah Enter ditekan dan tidak sedang mengetik di input
    if (event.key === "Enter" && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
        event.preventDefault(); // Mencegah submit jika ada
        document.getElementById('captureBtn').click(); // Trigger tombol ambil gambar
    }
});
document.querySelectorAll('.jumlah-input').forEach(input => {
    input.addEventListener('input', updateSubtotal);
});
function updateSubtotal() {
}
function renderSummary() {
    const summaryDiv = document.getElementById('summaryDetected');
    summaryDiv.innerHTML = '<h6 class="fw-bold">Total Sampah Terdeteksi:</h6>';
    const totalList = Object.entries(detectedCounter)
        .filter(([_, jumlah]) => jumlah > 0)
        .map(([key, jumlah]) => {
            const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            return `<p class="mb-1">${label}: <strong>${jumlah}</strong> kali</p>`;
        });
    if (totalList.length > 0) {
        summaryDiv.innerHTML += totalList.join('');
    } else {
        summaryDiv.innerHTML += `<p class="text-muted">Belum ada sampah yang terdeteksi.</p>`;
    }
}
// Hapus gambar terakhir dengan tombol Delete
document.addEventListener('keydown', function(event) {
    if (event.key === 'Delete' && capturedImages.length > 0) {
        const lastImage = capturedImages[0]; // Gambar terakhir paling atas

        // Kurangi jumlah sampah dari input
        lastImage.detected.forEach(item => {
            const input = document.querySelector(`input[name='jumlah_${item}']`);
            if (input) {
                const current = parseInt(input.value || '0');
                input.value = Math.max(0, current - 1);
                detectedCounter[item] = Math.max(0, (detectedCounter[item] || 1) - 1);
            }
        });

        // Hapus dari array dan render ulang
        capturedImages.shift(); // Hapus gambar paling atas
        renderCapturedImages();
        updateSubtotal();
        renderSummary();
    }
});
</script>
@endsection
