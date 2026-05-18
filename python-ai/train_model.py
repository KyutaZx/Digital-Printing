"""
Script training model deteksi blur menggunakan MobileNetV2 dan data sintetis.
Dataset asli diambil dari tf_flowers (built-in TensorFlow Datasets), lalu
sebagian gambar di-blur secara programatis untuk membuat kelas 'blur'.

Jalankan: venv_313/Scripts/python.exe train_model.py
Output: model.h5 (siap pakai oleh main.py)
"""

import os
import sys
import numpy as np
import tensorflow as tf
from tensorflow import keras
from tensorflow.keras import layers
from PIL import Image, ImageFilter
import io

# ---------------------------------------------------
# Konfigurasi
# ---------------------------------------------------
IMG_SIZE        = 224
BATCH_SIZE      = 32
EPOCHS          = 10
BLUR_RADIUS_MIN = 3
BLUR_RADIUS_MAX = 8
VALIDATION_SPLIT = 0.2
MODEL_OUTPUT    = "model.h5"
SEED            = 42

print("=" * 60)
print("  Digital Printing - Blur Detection Model Trainer")
print("=" * 60)
print(f"TensorFlow version : {tf.__version__}")
print(f"Image size         : {IMG_SIZE}x{IMG_SIZE}")
print(f"Epochs             : {EPOCHS}")
print(f"Output model       : {MODEL_OUTPUT}")
print()

# ---------------------------------------------------
# 1. Download dataset tf_flowers
# ---------------------------------------------------
print("[1/5] Mengunduh dataset tf_flowers ...")
import tensorflow_datasets as tfds

(ds_train_raw, ds_val_raw), ds_info = tfds.load(
    "tf_flowers",
    split=["train[:80%]", "train[80%:]"],
    as_supervised=True,
    with_info=True,
    shuffle_files=True,
)
num_classes_orig = ds_info.features["label"].num_classes
print(f"      Jumlah gambar train : {ds_train_raw.cardinality().numpy()}")
print(f"      Jumlah gambar val   : {ds_val_raw.cardinality().numpy()}")

# ---------------------------------------------------
# 2. Fungsi augmentasi & pembuatan data sintetis
# ---------------------------------------------------
def apply_random_blur_pil(image_tensor):
    """Terapkan Gaussian Blur dengan radius acak menggunakan PIL."""
    img_np = image_tensor.numpy().astype(np.uint8)
    pil_img = Image.fromarray(img_np)
    radius = np.random.uniform(BLUR_RADIUS_MIN, BLUR_RADIUS_MAX)
    blurred = pil_img.filter(ImageFilter.GaussianBlur(radius=radius))
    return np.array(blurred, dtype=np.float32)


def preprocess_as_sharp(image, label):
    """Resize gambar asli => label kelas 1 (sharp)."""
    image = tf.image.resize(image, [IMG_SIZE, IMG_SIZE])
    image = tf.cast(image, tf.float32) / 255.0
    return image, 1   # sharp


def preprocess_as_blur(image, label):
    """Blur gambar => label kelas 0 (blur)."""
    image = tf.image.resize(image, [IMG_SIZE, IMG_SIZE])
    image = tf.cast(image, tf.float32)

    blurred = tf.py_function(
        func=lambda x: apply_random_blur_pil(x),
        inp=[image],
        Tout=tf.float32,
    )
    blurred.set_shape([IMG_SIZE, IMG_SIZE, 3])
    blurred = blurred / 255.0
    return blurred, 0   # blur


# ---------------------------------------------------
# 3. Bangun dataset gabungan sharp + blur
# ---------------------------------------------------
print("[2/5] Membangun dataset sharp + blur ...")

AUTOTUNE = tf.data.AUTOTUNE

def make_combined_dataset(raw_ds):
    sharp_ds = raw_ds.map(preprocess_as_sharp, num_parallel_calls=AUTOTUNE)
    blur_ds  = raw_ds.map(preprocess_as_blur,  num_parallel_calls=AUTOTUNE)
    combined = sharp_ds.concatenate(blur_ds)
    combined = combined.shuffle(buffer_size=2000, seed=SEED)
    combined = combined.batch(BATCH_SIZE).prefetch(AUTOTUNE)
    return combined

train_ds = make_combined_dataset(ds_train_raw)
val_ds   = make_combined_dataset(ds_val_raw)
print("      Dataset berhasil dibuat.")

# ---------------------------------------------------
# 4. Bangun model MobileNetV2 + fine-tune
# ---------------------------------------------------
print("[3/5] Membangun model MobileNetV2 ...")

base_model = keras.applications.MobileNetV2(
    input_shape=(IMG_SIZE, IMG_SIZE, 3),
    include_top=False,
    weights="imagenet",
)
base_model.trainable = False   # Freeze dulu, fine-tune setelahnya

inputs  = keras.Input(shape=(IMG_SIZE, IMG_SIZE, 3))
x       = base_model(inputs, training=False)
x       = layers.GlobalAveragePooling2D()(x)
x       = layers.Dropout(0.3)(x)
outputs = layers.Dense(1, activation="sigmoid")(x)   # 0=blur, 1=sharp

model = keras.Model(inputs, outputs)
model.summary(print_fn=lambda s: None)   # quiet

model.compile(
    optimizer=keras.optimizers.Adam(1e-3),
    loss="binary_crossentropy",
    metrics=["accuracy"],
)
print("      Model berhasil dibuat.")

# ---------------------------------------------------
# 5. Training (Phase 1 — frozen base)
# ---------------------------------------------------
print(f"[4/5] Training Phase 1 ({EPOCHS} epoch, frozen base) ...")
callbacks = [
    keras.callbacks.EarlyStopping(
        monitor="val_accuracy", patience=3, restore_best_weights=True, verbose=1
    ),
    keras.callbacks.ReduceLROnPlateau(
        monitor="val_loss", factor=0.5, patience=2, verbose=1
    ),
]

history = model.fit(
    train_ds,
    validation_data=val_ds,
    epochs=EPOCHS,
    callbacks=callbacks,
    verbose=1,
)

# ---------------------------------------------------
# Fine-tune: unfreeze top 30 layers
# ---------------------------------------------------
print("[4/5] Fine-tune Phase 2 (unfreeze top 30 layers) ...")
base_model.trainable = True
for layer in base_model.layers[:-30]:
    layer.trainable = False

model.compile(
    optimizer=keras.optimizers.Adam(1e-5),
    loss="binary_crossentropy",
    metrics=["accuracy"],
)

history_ft = model.fit(
    train_ds,
    validation_data=val_ds,
    epochs=5,
    callbacks=callbacks,
    verbose=1,
)

# ---------------------------------------------------
# 6. Evaluasi & simpan
# ---------------------------------------------------
print("[5/5] Evaluasi model akhir ...")
loss, acc = model.evaluate(val_ds, verbose=0)
print(f"      Validation Loss     : {loss:.4f}")
print(f"      Validation Accuracy : {acc*100:.2f}%")

model.save(MODEL_OUTPUT)
print()
print("=" * 60)
print(f"  Model berhasil disimpan ke: {MODEL_OUTPUT}")
print(f"  Ukuran file: {os.path.getsize(MODEL_OUTPUT) / 1024 / 1024:.1f} MB")
print("=" * 60)
print()
print("Sekarang jalankan kembali: venv_313/Scripts/python.exe main.py")
