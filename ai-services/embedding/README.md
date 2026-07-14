# SIMORA Embedding Service

Layanan AI berbasis FastAPI untuk menghasilkan embedding vector menggunakan model HuggingFace `paraphrase-multilingual-MiniLM-L12-v2`.

## Cara Install

Pastikan Python 3.8+ sudah terinstall di sistem Anda, lalu jalankan perintah berikut:

```bash
pip install -r requirements.txt
```

## Cara Menjalankan Service

Jalankan server menggunakan Uvicorn di port `8001`:

```bash
uvicorn main:app --port 8001
```

## Endpoints

1. **GET `/health`**
   - Mengembalikan status kesehatan service.
   - Response: `{"status": "ok"}`

2. **POST `/embed`**
   - Payload: `{"text": "Isi laporan kegiatan pertanggungjawaban"}`
   - Response: `{"vector": [0.123, -0.456, ...]}`
