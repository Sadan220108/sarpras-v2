# SarPras v2: Pengajuan Sarana dan Prasarana (Laravel 12)

Sistem untuk mengisi dan mencetak formulir pengajuan sarana/prasarana. Tanda tangan tetap basah di kertas.

- **Pemohon**: mengisi pengajuan (bagian A sarana baru dan/atau bagian B perbaikan, boleh melampirkan berkas).
  Sistem membuat nomor surat otomatis, lalu mencetak surat dan dua Berita Acara
  ("Cetak atau simpan sebagai PDF" di halaman cetak). Pengajuan bisa dilihat, diubah, dan dicetak ulang.
- **Admin**: hanya mengelola akun pengguna (menu Pengguna).

## Menjalankan di lokal

Kebutuhan: PHP 8.2+ dan Composer (Laragon sudah memuatnya).

1. git clone https://github.com/Sadan220108/sarpras-v2.git
2. cd sarpras-v2
3. composer install
4. Copy-Item .env.example .env   (di Linux/Mac: cp .env.example .env)
5. php artisan key:generate
6. php artisan migrate --seed   (jika ditanya membuat database SQLite, jawab yes)
7. php artisan serve, lalu buka http://127.0.0.1:8000

Jika jam di tabel tampil mundur 7 jam, ubah `'timezone' => 'UTC'` di `config/app.php`
menjadi `'Asia/Jakarta'`, dan `APP_LOCALE=id` di `.env`.

## Akun contoh (password: password)

- pemohon@sarpras.test: mengisi dan mencetak pengajuan
- admin@sarpras.test: menambah dan mengubah akun pengguna

Akun ini hanya untuk percobaan lokal. Ganti passwordnya sebelum dipasang di server.

## Pengaturan surat

Di `config/sarpras.php`: kode unit nomor surat, nama kota, dan nama pejabat yang dicetak di bawah garis
tanda tangan. Kosongkan (`null`) agar hanya tercetak garis titik-titik.

## Catatan

- Lampiran disimpan di `storage/app/private`, hanya bisa diunduh pemiliknya setelah login.
- Tidak perlu `npm install`: CSS ada di `public/css/app.css`.