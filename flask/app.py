from flask import Flask, request, jsonify
from flask_cors import CORS
from ultralytics import YOLO
import cv2
import numpy as np
import base64

app = Flask(__name__)
CORS(app)

# Load model YOLO
model = YOLO("best.pt")

@app.route('/predict', methods=['POST'])
def predict():
    data = request.json
    if 'image' not in data:
        return jsonify({"error": "No image provided"}), 400

    # Decode base64 image
    img_data = base64.b64decode(data['image'])
    nparr = np.frombuffer(img_data, np.uint8)
    img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

    # Run model prediction with lower confidence threshold and relaxed IOU
    results = model.predict(source=img, conf=0.65, iou=0.3)
    result = results[0]

    # Extract boxes, class labels and confidence scores
    labels = result.boxes.cls.cpu().numpy().astype(int)
    confs = result.boxes.conf.cpu().numpy()
    names = result.names

    # Filter label berdasarkan confidence minimal 0.65
    detected = []
    for i, label_id in enumerate(labels):
        if confs[i] >= 0.65:
            detected.append(names[label_id])

    # Annotated image with flipped result (mirror effect)
    annotated_img = result.plot()
    annotated_img = cv2.flip(annotated_img, 1)
    _, buffer = cv2.imencode('.jpg', annotated_img)
    img_encoded = base64.b64encode(buffer).decode('utf-8')

    return jsonify({
        "detected": detected,
        "image_with_boxes": img_encoded
    })

if __name__ == '__main__':
    app.run(debug=True, host='127.0.0.1', port=5000)
