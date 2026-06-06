import os
os.environ["TF_USE_LEGACY_KERAS"] = "1"
import tensorflow as tf
print("TF version:", tf.__version__)
model = tf.keras.models.load_model("python-ai/model.h5")
print("Model loaded successfully!")
