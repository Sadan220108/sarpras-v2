<?php

// Pengaturan surat. Nama pejabat (jika diisi) dicetak di bawah garis tanda tangan.
// Kosongkan (null) agar yang tercetak hanya garis titik-titik.
return [
    'kode_unit' => 'SARPRAS',   // dipakai di nomor surat: 001/SARPRAS/X-2026
    'kota'      => 'Surabaya',

    'pejabat' => [
        'koordinator'      => ['jabatan' => 'Koordinator Sarana dan Prasarana', 'nama' => null],
        'kepala_bauk'      => ['jabatan' => 'Kepala BAUK', 'nama' => null],
        'asisten_direktur' => ['jabatan' => 'Asisten Direktur II', 'nama' => null],
    ],
];
