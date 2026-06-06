import tensorflow as tf
from tensorflow.keras.layers import BatchNormalization

class LegacyBatchNormalization(BatchNormalization):
    def __init__(self, renorm=False, renorm_clipping=None, renorm_momentum=0.99, **kwargs):
        super().__init__(**kwargs)

model = tf.keras.models.load_model(
    "python-ai/model.h5", 
    custom_objects={'BatchNormalization': LegacyBatchNormalization}
)
print("Model loaded successfully!")
