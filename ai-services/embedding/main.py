import os
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from sentence_transformers import SentenceTransformer

# Load model once at startup
print("Loading sentence-transformers model (paraphrase-multilingual-MiniLM-L12-v2)...")
model = SentenceTransformer("paraphrase-multilingual-MiniLM-L12-v2")
print("Model loaded successfully!")

app = FastAPI(title="SIMORA Embedding Service")

class EmbedRequest(BaseModel):
    text: str

@app.post("/embed")
def embed(request: EmbedRequest):
    if not request.text:
        raise HTTPException(status_code=400, detail="Text cannot be empty")
    try:
        vector = model.encode(request.text).tolist()
        return {"vector": vector}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.get("/health")
def health():
    return {"status": "ok"}
