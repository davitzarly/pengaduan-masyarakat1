#!/usr/bin/env python
import argparse
import json
import re
import sys
from pathlib import Path
import pickle


def clean_text(text: str) -> str:
    text = text.lower()
    text = re.sub(r"[^a-z0-9\s]", " ", text)
    text = re.sub(r"\s+", " ", text).strip()
    return text


def load_label_map(model_dir: Path) -> dict:
    label_map_path = model_dir / "kategori_label_map.json"
    if label_map_path.exists():
        try:
            return json.loads(label_map_path.read_text(encoding="utf-8"))
        except Exception:
            return {}
    return {}


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--model-dir", default=None)
    args = parser.parse_args()

    project_root = Path(__file__).resolve().parents[1]
    model_dir = Path(args.model_dir) if args.model_dir else project_root / "storage" / "app" / "ml_kategori"

    model_path = model_dir / "kategori_random_forest.pkl"
    tfidf_path = model_dir / "kategori_tfidf.pkl"
    if not model_path.exists() or not tfidf_path.exists():
        print(json.dumps({"error": f"Model files not found in {model_dir}"}))
        return 1

    try:
        model = pickle.loads(model_path.read_bytes())
        tfidf = pickle.loads(tfidf_path.read_bytes())
    except Exception as exc:
        print(json.dumps({"error": f"Failed to load model: {exc}"}))
        return 1

    try:
        payload = json.loads(sys.stdin.read() or "{}")
    except Exception as exc:
        print(json.dumps({"error": f"Invalid JSON input: {exc}"}))
        return 1

    subjek = str(payload.get("subjek", "") or "")
    pesan = str(payload.get("pesan", "") or "")
    text = str(payload.get("text", "") or (subjek + " " + pesan))
    text_clean = clean_text(text)

    try:
        X = tfidf.transform([text_clean])
        pred = model.predict(X)[0]
        score = None
        if hasattr(model, "predict_proba"):
            proba = model.predict_proba(X)
            if proba is not None and len(proba) > 0:
                row = proba[0]
                try:
                    score = float(max(row))
                except Exception:
                    score = None
        label_map = load_label_map(model_dir)
        label = label_map.get(str(pred), str(pred))
        output = {
            "kategori": label,
            "score": score,
        }
        print(json.dumps(output))
        return 0
    except Exception as exc:
        print(json.dumps({"error": f"Prediction failed: {exc}"}))
        return 1


if __name__ == "__main__":
    raise SystemExit(main())
