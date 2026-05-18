import os
import io
import uvicorn
import numpy as np
import tensorflow as tf
from fastapi import FastAPI, UploadFile, File, HTTPException
from PIL import Image
from contextlib import asynccontextmanager
from dotenv import load_dotenv

# Load environment variables from .env file if it exists
load_dotenv()

# Configuration
MODEL_PATH = os.getenv("MODEL_PATH", "model.h5")
APP_PORT = int(os.getenv("APP_PORT", 5000))
APP_HOST = os.getenv("APP_HOST", "0.0.0.0")

# Global variable for the model
model = None
CLASS_NAMES = ['blur', 'sharp']

@asynccontextmanager
async def lifespan(app: FastAPI):
    """
    Lifespan context manager for FastAPI.
    Handles startup and shutdown events.
    """
    global model
    print(f"Memulai Digital Printing AI Service...")
    
    if os.path.exists(MODEL_PATH):
        try:
            # Load the model
            model = tf.keras.models.load_model(MODEL_PATH)
            print(f"SUCCESS: Berhasil memuat model AI dari {MODEL_PATH}")
        except Exception as e:
            print(f"ERROR: Gagal memuat model AI: {e}")
    else:
        print(f"WARNING: File {MODEL_PATH} tidak ditemukan.")
        print("API akan berjalan dalam mode bypass (selalu me-return 'sharp').")
    
    yield
    # Clean up and release resources if needed
    print("Mematikan Digital Printing AI Service...")

app = FastAPI(
    title="Digital Printing AI Service",
    description="Microservice untuk deteksi kualitas gambar (Blur Detection) menggunakan TensorFlow.",
    version="1.0.0",
    lifespan=lifespan
)

@app.post("/predict-blur")
async def predict_blur(file: UploadFile = File(...)):
    """
    Endpoint untuk mendeteksi apakah gambar blur atau tajam (sharp).
    """
    # Jika model belum dimuat, gunakan logika bypass
    if model is None:
        return {
            "status": "sharp", 
            "confidence": 100.0, 
            "message": "Model tidak ditemukan (Bypass Mode)"
        }

    try:
        # Baca file ke dalam memori
        contents = await file.read()
        image = Image.open(io.BytesIO(contents))
        
        # 1. Metode Algoritma Klasik (Variance of Laplacian)
        # Sangat bagus untuk mendeteksi blur secara umum
        gray_image = image.convert('L')
        img_np = np.array(gray_image, dtype=np.float32)
        top = img_np[:-2, 1:-1]
        bottom = img_np[2:, 1:-1]
        left = img_np[1:-1, :-2]
        right = img_np[1:-1, 2:]
        center = img_np[1:-1, 1:-1]
        laplacian = top + bottom + left + right - 4 * center
        laplacian_var = float(laplacian.var())
        
        # 2. Metode Deep Learning (MobileNetV2 AI)
        if image.mode != "RGB":
            image = image.convert("RGB")
            
        # Resize ke 224x224 (ukuran input AI saat training)
        image = image.resize((224, 224))
        
        # Konversi ke numpy array dan normalisasi ke [0,1]
        img_array = tf.keras.preprocessing.image.img_to_array(image) / 255.0
        img_array = tf.expand_dims(img_array, 0)  # tambahkan dimensi batch
        
        # Prediksi — output: sigmoid scalar [0,1]
        predictions = model.predict(img_array, verbose=0)
        score = float(predictions[0][0])  # probabilitas "sharp"
        
        # 3. Penggabungan Keputusan (Ensemble)
        # AI kita dilatih dengan Gaussian Blur di dataset Bunga. 
        # Ia mungkin kesulitan dengan Motion Blur di jalanan malam hari.
        # Jadi kita gunakan Laplacian Variance sebagai filter pencegah lolos.
        LAPLACIAN_THRESHOLD = 150.0  # Semakin kecil var = semakin blur
        
        if score >= 0.5 and laplacian_var > LAPLACIAN_THRESHOLD:
            nama_kelas = "sharp"
            confidence = score * 100
        else:
            nama_kelas = "blur"
            # Jika AI salah duga sharp tapi laplacian mendeteksi blur
            if score >= 0.5:
                confidence = 85.0 # Kita beri tingkat keyakinan hardcode karena AI gagal
            else:
                confidence = (1 - score) * 100
                
        return {
            "status":     nama_kelas,
            "confidence": round(confidence, 2),
            "debug": {
                "ai_score": round(score, 4),
                "laplacian_variance": round(laplacian_var, 2)
            }
        }
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Gagal memproses gambar: {str(e)}")

@app.get("/")
def read_root():
    return {
        "message": "Digital Printing AI Service is Running",
        "port": APP_PORT,
        "model_loaded": model is not None
    }

if __name__ == "__main__":
    uvicorn.run("main:app", host=APP_HOST, port=APP_PORT, reload=True)
