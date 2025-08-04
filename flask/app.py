from flask import Flask, request, jsonify
from flask_cors import CORS
from ultralytics import YOLO
import cv2
import numpy as np
import base64
import os

app = Flask(__name__)
CORS(app)

# Load YOLO model
model_path = os.path.join(os.path.dirname(__file__), "best.pt")
model = YOLO(model_path)

@app.route("/")
def home():
    return jsonify({"message": "YOLO Flask API is running!"})

@app.route('/predict', methods=['POST'])
def predict():
    data = request.json
    if 'image' not in data:
        return jsonify({"error": "No image provided"}), 400

    # Decode base64 image
    img_data = base64.b64decode(data['image'])
    nparr = np.frombuffer(img_data, np.uint8)
    img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

    # Run model prediction
    results = model.predict(source=img, conf=0.65, iou=0.3)
    result = results[0]

    # Extract boxes, class labels and confidence scores
    labels = result.boxes.cls.cpu().numpy().astype(int)
    confs = result.boxes.conf.cpu().numpy()
    names = result.names

    # Filter label berdasarkan confidence minimal 0.65
    detected = [names[label_id] for i, label_id in enumerate(labels) if confs[i] >= 0.65]

    # Annotated image with mirror effect
    annotated_img = result.plot()
    annotated_img = cv2.flip(annotated_img, 1)
    _, buffer = cv2.imencode('.jpg', annotated_img)
    img_encoded = base64.b64encode(buffer).decode('utf-8')

    return jsonify({
        "detected": detected,
        "image_with_boxes": img_encoded
    })

# Hugging Face akan otomatis menjalankan app.py menggunakan gunicorn/uvicorn
if __name__ == "__main__":
    app.run(host='0.0.0.0', port=7860)
