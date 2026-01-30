from flask import Flask, jsonify, request
from pathlib import Path
import json
import os
import pickle
import re

app = Flask(__name__)

BASE_DIR = Path(__file__).resolve().parents[1]
ML_MODEL_DIR = Path(os.getenv("ML_MODEL_DIR", BASE_DIR / "storage" / "app" / "ml"))
KATEGORI_MODEL_DIR = Path(
    os.getenv("KATEGORI_MODEL_DIR", BASE_DIR / "storage" / "app" / "ml_kategori")
)

_dibaca_cache = {}
_kategori_cache = {}


def clean_text(text: str) -> str:
    text = text.lower()
    text = re.sub(r"[^a-z0-9\s]", " ", text)
    text = re.sub(r"\s+", " ", text).strip()
    return text


def load_label_map(path: Path, default=None) -> dict:
    if path.exists():
        try:
            return json.loads(path.read_text(encoding="utf-8"))
        except Exception:
            return default or {}
    return default or {}


def load_model_cache(cache: dict, model_dir: Path, model_name: str, tfidf_name: str):
    cache_key = str(model_dir)
    if cache_key in cache:
        return cache[cache_key]

    model_path = model_dir / model_name
    tfidf_path = model_dir / tfidf_name
    if not model_path.exists() or not tfidf_path.exists():
        return None

    model = pickle.loads(model_path.read_bytes())
    tfidf = pickle.loads(tfidf_path.read_bytes())
    cache[cache_key] = (model, tfidf)
    return cache[cache_key]


@app.get("/health")
def health():
    return jsonify({"status": "ok"})


@app.get("/")
def index():
    return jsonify(
        {
            "status": "ok",
            "endpoints": [
                {"method": "GET", "path": "/health"},
                {"method": "POST", "path": "/predict/dibaca"},
                {"method": "POST", "path": "/predict/kategori"},
            ],
        }
    )


@app.post("/predict/dibaca")
def predict_dibaca():
    payload = request.get_json(silent=True) or {}
    subjek = str(payload.get("subjek", "") or "")
    pesan = str(payload.get("pesan", "") or "")
    text = str(payload.get("text", "") or (subjek + " " + pesan))

    model_data = load_model_cache(_dibaca_cache, ML_MODEL_DIR, "random_forest.pkl", "tfidf.pkl")
    if not model_data:
        return jsonify({"error": f"Model files not found in {ML_MODEL_DIR}"}), 400

    model, tfidf = model_data
    text_clean = clean_text(text)
    X = tfidf.transform([text_clean])
    pred = int(model.predict(X)[0])

    score = None
    if hasattr(model, "predict_proba"):
        proba = model.predict_proba(X)
        if proba is not None and len(proba) > 0 and pred < len(proba[0]):
            score = float(proba[0][pred])

    label_map = load_label_map(ML_MODEL_DIR / "label_map.json", {"0": "N", "1": "Y"})
    label = label_map.get(str(pred), "N")
    label_text = "Dibaca" if label == "Y" else "Tidak Dibaca"

    return jsonify(
        {
            "label": label,
            "label_text": label_text,
            "score": score,
        }
    )


@app.post("/predict/kategori")
def predict_kategori():
    payload = request.get_json(silent=True) or {}
    subjek = str(payload.get("subjek", "") or "")
    pesan = str(payload.get("pesan", "") or "")
    text = str(payload.get("text", "") or (subjek + " " + pesan))

    model_data = load_model_cache(
        _kategori_cache, KATEGORI_MODEL_DIR, "kategori_random_forest.pkl", "kategori_tfidf.pkl"
    )
    if not model_data:
        return jsonify({"error": f"Model files not found in {KATEGORI_MODEL_DIR}"}), 400

    model, tfidf = model_data
    text_clean = clean_text(text)
    X = tfidf.transform([text_clean])
    pred = model.predict(X)[0]

    score = None
    if hasattr(model, "predict_proba"):
        proba = model.predict_proba(X)
        if proba is not None and len(proba) > 0:
            score = float(max(proba[0]))

    label_map = load_label_map(KATEGORI_MODEL_DIR / "kategori_label_map.json", {})
    label = label_map.get(str(pred), str(pred))

    return jsonify(
        {
            "kategori": label,
            "score": score,
        }
    )


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=int(os.getenv("PORT", "5001")))
