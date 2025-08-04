<input type="file" id="imgInput">
<button onclick="uploadImage()">Deteksi</button>

<script>
function uploadImage() {
    const input = document.getElementById('imgInput');
    const file = input.files[0];
    const formData = new FormData();
    formData.append('image', file);

    fetch('http://127.0.0.1:5000/predict', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log('Deteksi:', data.detections);
        alert(JSON.stringify(data.detections, null, 2));
    })
    .catch(err => {
        console.error('Error:', err);
    });
}
</script>
