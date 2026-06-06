import os
os.environ["TF_USE_LEGACY_KERAS"] = "1"
import urllib.request
import io
import numpy as np
import tensorflow as tf
from PIL import Image

def test_image(url):
    print("Downloading image...")
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    response = urllib.request.urlopen(req)
    contents = response.read()
    image = Image.open(io.BytesIO(contents))
    
    # 1. Laplacian
    gray_image = image.convert('L')
    img_np = np.array(gray_image, dtype=np.float32)
    top = img_np[:-2, 1:-1]
    bottom = img_np[2:, 1:-1]
    left = img_np[1:-1, :-2]
    right = img_np[1:-1, 2:]
    center = img_np[1:-1, 1:-1]
    laplacian = top + bottom + left + right - 4 * center
    laplacian_var = float(laplacian.var())
    
    print(f"Laplacian Var: {laplacian_var}")
    
    # 2. AI Model
    print("Loading model...")
    model = tf.keras.models.load_model("python-ai/model.h5")
    
    if image.mode != "RGB":
        image = image.convert("RGB")
    image = image.resize((224, 224))
    img_array = tf.keras.preprocessing.image.img_to_array(image) / 255.0
    img_array = tf.expand_dims(img_array, 0)
    
    print("Predicting...")
    predictions = model.predict(img_array, verbose=0)
    score = float(predictions[0][0])
    
    print(f"AI Score: {score}")

if __name__ == "__main__":
    test_image("http://jayamandiri.my.id/api-proxy/uploads/designs/1780717437_ftblur1.jpg")
