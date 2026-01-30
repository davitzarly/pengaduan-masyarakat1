<?php

return [
    'python' => env('ML_PYTHON', 'python'),
    'script' => base_path('tools/predict_dibaca.py'),
    'model_dir' => env('ML_MODEL_DIR', storage_path('app/ml')),
    'api_url' => env('ML_API_URL', ''),
];
