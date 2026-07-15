# Pedoman Konsistensi Desain — SIMORA

Dokumen ini berisi panduan untuk menjaga konsistensi visual dan antarmuka pengguna (UI/UX) pada sistem **SIMORA (Sistem Manajemen Persuratan OSIS)**. Semua pembaruan halaman, komponen baru, atau modifikasi tata letak di masa mendatang wajib mengikuti standar di bawah ini.

---

## 1. Identitas Brand (Logo & Favicon)

### Logo Utama
- **File**: `public/assets/images/SIMORA.png` (Format PNG transparan).
- **Karakteristik**: Logo berupa huruf outline berjalur/sirkuit dengan garis luar merah dan bagian dalam kosong/putih.
- **Penerapan**: 
  - Wajib diletakkan langsung di atas latar belakang merah tema (`#E62129`), seperti di panel kiri halaman masuk/keluar dan bagian atas sidebar utama.
  - Jangan membungkus logo dengan kotak atau lencana putih pada latar belakang merah, karena garis luar merah logo dirancang untuk menyatu secara alami dengan latar belakang merah sehingga karakter putih di dalamnya menonjol secara bersih.
  - Pada halaman berlatar belakang putih/terang (seperti dokumen PDF), logo dapat langsung dimuat dan garis luar merah akan kontras secara otomatis.

### Favicon Tab Browser
- **File**: `public/assets/images/logo-tab.svg` (Format SVG).
- **Desain**: Monogram inisial huruf "S" bergaya trek/sirkuit berwarna merah.
- **Penerapan**: Didokumentasikan di bagian `<head>` pada seluruh file tata letak utama (`layouts/master.blade.php`, `layouts/app.blade.php`, dll.) menggunakan tag:
  ```html
  <link rel="icon" type="image/svg+xml" href="{{ URL::to('assets/images/logo-tab.svg') }}">
  ```

---

## 2. Palet Warna & Token Desain

Gunakan warna-warna berikut untuk menjaga konsistensi visual:

| Token | Warna CSS / HEX | Deskripsi | Penggunaan |
| :--- | :--- | :--- | :--- |
| **Primary (Merah)** | `#E62129` | Warna utama SIMORA | Tombol aksi utama, latar belakang sidebar, panel kiri login/logout |
| **Primary Dark** | `#C91A20` | Variasi gelap merah | Efek hover/aktif tombol primary |
| **Text Utama** | `#111111` | Hitam pekat | Judul halaman, teks tebal, nama kolom tabel |
| **Text Muted** | `#6B7280` | Abu-abu medium | Subjudul, tanggal, teks deskripsi, placeholder input |
| **Background Light** | `#F5F5F7` / `#F7F7F7` | Abu-abu sangat terang | Latar belakang halaman utama, panel kanan login/logout |
| **Card / Container Grey** | `#E5E7EB` | Abu-abu abu semen lembut | Kartu statistik dashboard, input pencarian, pembungkus tabel |
| **Surface (Putih)** | `#FFFFFF` | Putih bersih | Kartu login/logout, baris data tabel, menu aktif sidebar |

---

## 3. Tata Letak Autentikasi (Split-Panel Layout)

Halaman login dan logout menggunakan sistem panel terpisah (*split-panel*) dengan proporsi **1.1 (Kiri) : 1 (Kanan)**.

### Panel Kiri (Merah - Informasi & Dekorasi)
- **Background**: `#E62129` (Merah).
- **Elemen Wajib**:
  - Logo SIMORA transparan (`SIMORA.png`) diletakkan di sudut kiri atas dengan margin responsif.
  - Judul sambutan besar berukuran font `60px` dengan berat `800` (contoh: *Selamat Datang* atau *Sampai Jumpa*).
  - Dekorasi lingkaran putih besar (`border: 55px solid #FFFFFF`) yang melengkung di sudut kiri bawah (`ring-bottom-left`).
  - Kluster lencana organisasi sekolah (MPK, OSIS, Pramuka) berbentuk lingkaran putih dengan efek bayangan lembut.
  - Titik-titik putih dekoratif (`dot-decor`) di sekitar lencana.
- **Responsif**: Pada lebar layar `< 768px`, panel kiri melipat ke atas secara vertikal, menyembunyikan kluster lencana, dan mengubah judul sambutan menjadi ukuran `40px` terpusat.

### Panel Kanan (Terang - Formulir / Kartu Aksi)
- **Background**: `#F7F7F7` (Abu-abu terang).
- **Elemen Wajib**:
  - Dekorasi lingkaran merah besar (`border: 65px solid #E62129`) yang melengkung di sudut kanan atas.
  - Kartu putih di tengah (`background: #FFFFFF`, `border-radius: 24px`, `box-shadow: 0 10px 40px rgba(0,0,0,0.03)`).
  - Judul kartu di tengah berukuran `28px` dengan berat `800`.
  - Tombol aksi berbentuk kapsul penuh / pill (`border-radius: 9999px`) dengan efek hover merah gelap.

---

## 4. Sidebar Navigasi Utama

Sidebar ditempatkan di sisi kiri layar dengan lebar tetap **240px** dan latar belakang merah tema (`#E62129`).

### Struktur Menu
1. **Logo Area**: Menampilkan `SIMORA.png` transparan tanpa wadah putih, dibungkus tautan ke halaman beranda (`/home`).
2. **Menu Utama**:
   - **Dashboard**: Mengarah ke halaman utama ringkasan sistem.
   - **Ajukan surat**: Mengarah langsung ke formulir pembuatan surat baru.
   - **Persetujuan**: Menampilkan surat-surat yang sedang menunggu persetujuan pengguna (`filter=waiting`).
   - **Daftar surat**: Menampilkan seluruh surat yang dapat diakses oleh pengguna.
   - **Fitur Tambahan**: Halaman monitoring Pelaksanaan, Arsip LPJ, dan Profil Saya.
   - **Fitur Admin**: Panel khusus yang hanya muncul untuk admin (Organisasi, Jenis Surat, Sistem, dll.).
3. **Logout**: Tombol keluar ditempatkan di bagian paling bawah sidebar.

### Efek Transisi Pill Aktif
- Menu yang sedang aktif ditandai dengan **slider latar belakang putih** (`background: #FFFFFF`) yang melengkung penuh di sisi kanan (`border-radius: 0 9999px 9999px 0`).
- Teks dan ikon menu yang aktif otomatis berubah menjadi hitam pekat (`#000000`) untuk kontras yang jelas.
- Perpindahan slider didukung oleh transisi CSS yang mulus (`transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1)`).

---

## 5. Halaman Dashboard Terpadu (Unified Dashboard)

Dashboard utama dirancang untuk menggabungkan data statistik ringkas dengan daftar dokumen persuratan dalam satu tampilan terpadu.

### Kartu Statistik Utama (Top Stats Grid)
- Disusun dalam grid 3 kolom (1 kolom pada mobile).
- Menggunakan latar belakang abu-abu semen lembut (`#E5E7EB`) dengan lengkungan sudut `28px`.
- Ukuran angka statistik berukuran `56px` dengan berat `800` menggunakan warna `#111111`.
- Label keterangan diletakkan di bawah angka menggunakan huruf kapital abu-abu gelap berukuran `xs`.

### Komponen Tabel Dokumen
- Dibungkus dalam container berwarna abu-abu lembut transparan (`bg-[#E5E7EB]/40`, `border-radius: 28px`).
- Baris tabel (`<tr>`) memiliki latar belakang transparan dan efek hover abu-abu lembut (`hover:bg-gray-200/40`).
- Teks perihal surat dicetak tebal (`font-medium`) untuk keterbacaan yang optimal.
- Status dokumen wajib menggunakan lencana pill dengan warna yang konsisten:
  - **Diajukan**: Latar biru (`bg-blue-100`, text-blue-700).
  - **Disetujui**: Latar hijau (`bg-green-100`, text-green-700).
  - **Ditolak**: Latar merah (`bg-red-100`, text-red-700).
  - **Revisi**: Latar kuning (`bg-amber-100`, text-amber-700).
  - **Menunggu Admin**: Latar abu-abu (`bg-gray-100`, text-gray-600).

---

## 6. Elemen Input & Tombol

Semua tombol dan kolom input dalam SIMORA wajib menggunakan bentuk **pill / kapsul** atau **sudut melengkung ekstrem** untuk kenyamanan visual:

- **Kolom Input Pencarian**: `border-radius: 16px` (2xl) atau `border-radius: 9999px` (pill) dengan warna latar belakang abu-abu lembut (`#E5E7EB`) dan tanpa border garis.
- **Tombol Utama**: `border-radius: 16px` (2xl) atau `border-radius: 9999px` (pill) berwarna merah `#E62129` dengan efek bayangan (*shadow*) halus dan transisi hover yang mulus.
- **Skeleton Loader**: Gunakan komponen skeleton beranimasi gradien (*shimmer*) pada tabel atau komponen besar saat memuat data dari database untuk mengurangi *cognitive load* pengguna.
