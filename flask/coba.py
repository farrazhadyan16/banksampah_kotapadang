import cv2
import requests
import base64
import numpy as np

# URL API Hugging Face (ganti dengan URL Space kamu)
API_URL = "https://farraz16-banksampah.hf.space/predict"

# Buka kamera (0 untuk webcam default)
cap = cv2.VideoCapture(0)

while True:
    ret, frame = cap.read()
    if not ret:
        break

    # Resize agar tidak terlalu berat
    frame_resized = cv2.resize(frame, (640, 480))

    # Encode ke JPEG lalu ke base64
    _, buffer = cv2.imencode(".jpg", frame_resized)
    img_base64 = base64.b64encode(buffer).decode("utf-8")

    try:
        # Kirim ke API Hugging Face
        response = requests.post(API_URL, json={"image": img_base64}, timeout=10)
        data = response.json()

        # Decode image hasil deteksi dari API
        if "image_with_boxes" in data:
            img_data = base64.b64decode(data["image_with_boxes"])
            nparr = np.frombuffer(img_data, np.uint8)
            detected_img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

            # Tampilkan hasil
            cv2.imshow("YOLO Detection", detected_img)
        else:
            # Jika API belum mengirim gambar
            cv2.imshow("YOLO Detection", frame_resized)

    except Exception as e:
        print("Error:", e)
        cv2.imshow("YOLO Detection", frame_resized)

    # Tekan 'q' untuk keluar
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
