from ultralytics import YOLO

model = YOLO('best.pt')

def detect_objects(image):
    results = model(image)[0]
    classes = [model.names[int(cls)] for cls in results.boxes.cls]
    return {"detected": list(set(classes))}
