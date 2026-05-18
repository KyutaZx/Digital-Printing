import os
import tensorflow as tf
from dotenv import load_dotenv

load_dotenv()
MODEL_PATH = os.getenv("MODEL_PATH", "model.h5")

if os.path.exists(MODEL_PATH):
    model = tf.keras.models.load_model(MODEL_PATH)
    print("Model loaded successfully.")
    print("Model Input Shape:", model.input_shape)
    print("Model Output Shape:", model.output_shape)
    
    # Check if the first layer includes Rescaling or if it expects normalized input
    for layer in model.layers:
        print(layer.name, layer.__class__.__name__)
else:
    print(f"Model not found at {MODEL_PATH}")
