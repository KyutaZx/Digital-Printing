import os
import tensorflow as tf
from PIL import Image
import numpy as np

# Load model
MODEL_PATH = "model.h5"
image_path = r"d:\laravel-project\web-digital-printing\golang-api\uploads\designs\1779132243_ptblur2.jpg"

if not os.path.exists(MODEL_PATH):
    print("Model not found at", MODEL_PATH)
    exit(1)

if not os.path.exists(image_path):
    print("Image not found at", image_path)
    exit(1)

model = tf.keras.models.load_model(MODEL_PATH)
print("Model loaded successfully.")

# Load and preprocess image
image = Image.open(image_path)
if image.mode != "RGB":
    image = image.convert("RGB")
image = image.resize((224, 224))

img_array = tf.keras.preprocessing.image.img_to_array(image) / 255.0
img_array = tf.expand_dims(img_array, 0)

# Predict
predictions = model.predict(img_array, verbose=0)
score = float(predictions[0][0])
print(f"Sigmoid score (prob of sharp): {score}")
if score >= 0.5:
    print(f"Class: SHARP with confidence {score * 100:.2f}%")
else:
    print(f"Class: BLUR with confidence {(1 - score) * 100:.2f}%")
