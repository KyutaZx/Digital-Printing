import os
import io
import numpy as np
import tensorflow as tf
from fastapi import FastAPI, UploadFile, File, HTTPException
from PIL import Image

app = FastAPI(title="Digital Printing AI Service")

# Path ke file model (pastikan file blur_detector.h5 atau model.h5 ada di direktori ini)
MODEL_PATH = "model.h5"

# Variabel global untuk menyimpan model di memori
model = None

# Class names sesuai dengan yang ditraining di Colab
CLASS_NAMES = ['blur', 'sharp']

@app.on_event("startup")
async def load_model():
    global model
    if os.path.exists(MODEL_PATH):
        try:
            model = tf.keras.models.load_model(MODEL_PATH)
            print("Berhasil memuat model AI!")
        except Exception as e:
            print(f"Gagal memuat model AI: {e}")
    else:
        print(f"WARNING: File {MODEL_PATH} tidak ditemukan. API akan selalu me-return 'sharp' untuk sementara.")

@app.post("/predict-blur")
async def predict_blur(file: UploadFile = File(...)):
    # Jika model belum ada, kembalikan 'sharp' sebagai bypass sementara
    if model is None:
        return {"status": "sharp", "confidence": 100.0, "message": "Model tidak ditemukan (Bypass)"}

    try:
        # Baca file ke dalam memori
        contents = await file.read()
        image = Image.open(io.BytesIO(contents))
        
        # Konversi ke RGB jika gambar memiliki Alpha channel (PNG/RGBA)
        if image.mode != "RGB":
            image = image.convert("RGB")
            
        # Resize ke 224x224 (ukuran input AI saat training)
        image = image.resize((224, 224))
        
        # Konversi ke numpy array dan tambahkan dimensi batch
        img_array = tf.keras.preprocessing.image.img_to_array(image)
        img_array = tf.expand_dims(img_array, 0)
        
        # Prediksi
        predictions = model.predict(img_array)
        score = tf.nn.softmax(predictions[0])
        
        nama_kelas = CLASS_NAMES[np.argmax(score)]
        persentase = 100 * np.max(score)
        
        return {
            "status": nama_kelas,
            "confidence": float(persentase)
        }
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Gagal memproses gambar: {str(e)}")

@app.get("/")
def read_root():
    return {"message": "Digital Printing AI Service is Running"}
