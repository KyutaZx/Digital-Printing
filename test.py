import numpy as np
from PIL import Image

def test_blur(img_path):
    image = Image.open(img_path)
    gray_image = image.convert('L')
    img_np = np.array(gray_image, dtype=np.float32)
    top = img_np[:-2, 1:-1]
    bottom = img_np[2:, 1:-1]
    left = img_np[1:-1, :-2]
    right = img_np[1:-1, 2:]
    center = img_np[1:-1, 1:-1]
    laplacian = top + bottom + left + right - 4 * center
    laplacian_var = float(laplacian.var())
    print(f"Variance of Laplacian for {img_path}: {laplacian_var}")

if __name__ == '__main__':
    test_blur("1780716727_ftblur1.jpg")
