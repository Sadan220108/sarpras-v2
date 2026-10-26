# SarPras v2: Pengajuan Sarana dan Prasarana (Laravel 12, lokal)

Sistem untuk mengisi dan mencetak formulir pengajuan sarana/prasarana. Tanda tangan tetap basah di kertas.
- **Pemohon**: mengisi pengajuan, sistem membuat nomor surat otomatis, lalu mencetak surat dan dua Berita Acara
  ("Cetak atau simpan sebagai PDF" di halaman cetak). Pengajuan bisa dilihat, diubah, dan dicetak ulang.
- **Admin**: hanya mengelola akun pengguna (menu Pengguna).

Folder ini hanya berisi file milik aplikasinya. Rangka Laravel 12 dipasang dulu, lalu isi folder ini disalin menimpanya.

## Langkah (instalasi baru)
1. composer create-project laravel/laravel:^12.0 sarpras-v2
2. Salin seluruh isi folder ini ke dalam `sarpras-v2`. Pilih "replace" jika ditanya.
3. Di `config/app.php`: ubah `'timezone' => 'UTC'` menjadi `'timezone' => 'Asia/Jakarta'`.
   Di `.env`: ubah `APP_LOCALE=en` menjadi `APP_LOCALE=id`.
4. php artisan migrate --seed   (jika ditanya membuat database SQLite, jawab yes)
5. php artisan serve, lalu buka http://127.0.0.1:8000

## Jika sebelumnya sudah memasang versi lain
1. Hapus file lama jika ada: `app/Models/Persetujuan.php`, `app/Models/Riwayat.php`,
   `app/Http/Controllers/PersetujuanController.php`, `app/Http/Controllers/StatusController.php`.
2. Salin isi folder ini (replace).
3. php artisan migrate:fresh --seed
   PERHATIAN: perintah ini menghapus semua isi database lokal (data contoh saja) lalu membuatnya ulang.

## Akun contoh (password: password)
- pemohon@sarpras.test (mengisi dan mencetak pengajuan)
- admin@sarpras.test (menambah dan mengubah akun pengguna)

## Pengaturan surat
Di `config/sarpras.php`: kode unit nomor surat, nama kota, dan nama pejabat yang dicetak di bawah garis
tanda tangan (Koordinator SarPras, Kepala BAUK, Asisten Direktur II). Kosongkan agar hanya tercetak garis titik-titik.

## Skenario uji
1. Admin: buka menu Pengguna, tambah akun pemohon baru (isi nama, jabatan, dan unit karena tampil di surat).
2. Pemohon: buat pengajuan (bagian A dan/atau B, boleh melampirkan berkas). Coba kosongkan spesifikasi dan link
   (harus ditolak sistem).
3. Buka pengajuan, "Cetak surat dan berita acara": tiga halaman dengan kolom tanda tangan kosong.
4. Ubah isi pengajuan (nomor surat tetap), lalu cetak ulang.
5. Pengajuan Saya: coba kolom cari. Pastikan pemohon tidak bisa membuka pengajuan milik pemohon lain.

## Catatan
- Data dari project lama (Laravel 10) tidak ikut terbawa.
- Lampiran disimpan di `storage/app/private`, hanya bisa diunduh pemiliknya setelah login.
- Ganti password akun contoh sebelum dipasang di server.
