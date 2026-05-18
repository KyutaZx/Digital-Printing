import numpy as np
from PIL import Image

def calculate_laplacian_variance_pure_numpy(image_path):
    # Load image as grayscale
    img = Image.open(image_path).convert('L')
    img_np = np.array(img, dtype=np.float32)
    
    # Laplacian filter using pure numpy slicing (faster and zero-dependency)
    # kernel:
    # [ 0,  1,  0]
    # [ 1, -4,  1]
    # [ 0,  1,  0]
    
    # We shift the image up, down, left, right and combine
    top   = img_np[:-2, 1:-1]
    bottom = img_np[2:, 1:-1]
    left   = img_np[1:-1, :-2]
    right  = img_np[1:-1, 2:]
    center = img_np[1:-1, 1:-1]
    
    laplacian = top + bottom + left + right - 4 * center
    
    # Variance
    variance = laplacian.var()
    return variance

# Test on the uploaded blurry image
image_path = r"d:\laravel-project\web-digital-printing\golang-api\uploads\designs\1779132243_ptblur2.jpg"
try:
    var = calculate_laplacian_variance_pure_numpy(image_path)
    print(f"Laplacian Variance of blurry image: {var:.2f}")
except Exception as e:
    print("Error:", e)
