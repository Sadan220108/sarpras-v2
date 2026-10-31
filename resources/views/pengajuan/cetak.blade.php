<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak {{ $pengajuan->nomor }}</title>
    <style>
        body { margin: 0; background: #e5e7eb; font-family: "Times New Roman", serif; font-size: 12pt; color: #000; }
        .page { width: 190mm; min-height: 270mm; margin: 10px auto; padding: 15mm 18mm; background: #fff; box-sizing: border-box; page-break-after: always; }
        .page:last-child { page-break-after: auto; }
        h2 { text-align: center; font-size: 13pt; text-decoration: underline; margin: 0 0 14px; }
        p { margin: 8px 0; }
        table { border-collapse: collapse; }
        .kop td { padding: 1px 6px 1px 0; vertical-align: top; }
        .t { width: 100%; margin: 6px 0; }
        .t th, .t td { border: 1px solid #000; padding: 4px 6px; font-size: 11pt; vertical-align: top; }
        .t th { background: #f3f4f6; text-align: center; }
        .note { font-size: 10pt; font-style: italic; margin: 2px 0 10px; }
        .ttd { width: 100%; margin-top: 18px; text-align: center; }
        .ttd td { width: 50%; vertical-align: top; padding: 6px 4px 14px; }
        .ruang { display: block; height: 70px; }
        .nama { display: block; font-weight: bold; text-decoration: underline; min-height: 1.2em; }
        .noprint { text-align: center; padding: 10px; font-family: sans-serif; }
        .noprint button { padding: 8px 18px; font-size: 14px; }
        @media print { body { background: #fff; } .page { margin: 0; width: auto; min-height: 0; padding: 0; } .noprint { display: none; } }
    </style>
</head>
<body>
@php
    $rp = fn ($n) => number_format($n, 0, ',', '.');
    $tglSurat = $pengajuan->tanggal->copy()->locale('id')->translatedFormat('j F Y');
    $kota = config('sarpras.kota', 'Surabaya');

    // Blok tanda tangan kosong: judul, ruang untuk tanda tangan basah, nama (jika diisi di config/sarpras.php)
    $blok = function ($judul, $kunci) {
        $nama = config("sarpras.pejabat.$kunci.nama");
        return '<td>'.$judul.'<span class="ruang"></span><span class="nama">'.($nama ? e($nama) : '........................').'</span></td>';
    };
    $pemohonBlok = '<td>Pemohon<span class="ruang"></span><span class="nama">'.e($pengajuan->pemohon->name).'</span></td>';

    // Baris gabungan untuk berita acara
    $baris = collect();
    foreach ($pengajuan->items as $i) {
        $baris->push(['nama' => $i->nama_barang, 'jumlah' => $i->jumlah, 'ket' => $i->keterangan ?: $i->spesifikasi]);
    }
    foreach ($pengajuan->perbaikan as $r) {
        $baris->push(['nama' => $r->nama_sarana, 'jumlah' => 1, 'ket' => $r->kerusakan.($r->lokasi ? ' ('.$r->lokasi.')' : '')]);
    }
@endphp

<div class="noprint">
    <button onclick="window.print()">Cetak atau simpan sebagai PDF</button>
</div>

{{-- Halaman 1: surat permohonan --}}
<div class="page">
    <table class="kop">
        <tr><td>Nomor</td><td>:</td><td>{{ $pengajuan->nomor }}</td></tr>
        <tr><td>Lampiran</td><td>:</td><td>{{ $pengajuan->lampiran->count() ? $pengajuan->lampiran->count().' berkas' : '-' }}</td></tr>
        <tr><td>Hal</td><td>:</td><td>Pengajuan/Perbaikan Sarana dan Prasarana</td></tr>
    </table>
    <p>Kepada Yth.<br>{{ config('sarpras.pejabat.asisten_direktur.jabatan') }}</p>
    <p>Melalui surat ini, saya yang bertanda tangan di bawah ini:</p>
    <table class="kop">
        <tr><td>Nama</td><td>:</td><td>{{ $pengajuan->pemohon->name }}</td></tr>
        <tr><td>Jabatan</td><td>:</td><td>{{ $pengajuan->jabatan }}</td></tr>
        <tr><td>Unit Bagian</td><td>:</td><td>{{ $pengajuan->unit }}</td></tr>
    </table>
    <p>Bermaksud mengajukan permohonan di unit bagian saya dengan rincian sebagai berikut:</p>

    <p><b>A. Pengajuan Sarana Baru</b></p>
    <table class="t">
        <tr><th>Nama Barang</th><th>Spesifikasi</th><th>Estimasi Biaya</th><th>Jumlah</th><th>Keterangan</th></tr>
        @forelse ($pengajuan->items as $i)
            <tr>
                <td style="text-align:center">{{ $i->nama_barang }}</td>
                <td style="text-align:center">{{ $i->spesifikasi }}@if ($i->link)<br><small>{{ $i->link }}</small>@endif</td>
                <td style="text-align:center">{{ $rp($i->biaya) }}</td>
                <td style="text-align:center">{{ $i->jumlah }}</td>
                <td>{{ $i->keterangan }}</td>
            </tr>
        @empty
            <tr><td colspan="5" style="text-align:center">-</td></tr>
        @endforelse
    </table>
    <p class="note">*wajib dilampirkan spesifikasi barang atau link marketplace</p>

    <p><b>B. Pengajuan Perbaikan Sarana/Prasarana</b></p>
    <table class="t">
        <tr><th>Nama Sarana/Prasarana</th><th>Kerusakan</th><th>Lokasi</th><th>Estimasi Biaya</th><th>Keterangan</th></tr>
        @forelse ($pengajuan->perbaikan as $r)
            <tr>
                <td style="text-align:center">{{ $r->nama_sarana }}</td><td style="text-align:center">{{ $r->kerusakan }}</td><td style="text-align:center">{{ $r->lokasi }}</td>
                <td style="text-align:center">{{ $rp($r->biaya) }}</td><td>{{ $r->keterangan }}</td>
            </tr>
        @empty
            <tr><td colspan="5" style="text-align:center">-</td></tr>
        @endforelse
    </table>

    <p>Demikian surat ini dibuat, terima kasih atas waktu dan perhatiannya.</p>
    <p>{{ $kota }}, {{ $tglSurat }}</p>
    <table class="ttd">
        <tr>
            {!! $pemohonBlok !!}
            {!! $blok('Koordinator SarPras', 'koordinator') !!}
        </tr>
        <tr>
            {!! $blok('Mengetahui,<br>Kepala BAUK', 'kepala_bauk') !!}
            {!! $blok('Menyetujui,<br>Asisten Direktur II', 'asisten_direktur') !!}
        </tr>
    </table>
</div>

{{-- Halaman 2: Berita Acara Kerusakan (dibuat pemohon) --}}
<div class="page">
    <h2>BERITA ACARA KERUSAKAN SARANA DAN PRASARANA</h2>
    <table class="t">
        <tr><td style="width:30%">Jenis</td><td>Sarana/Prasarana</td></tr>
        <tr><td>Nama SarPras</td><td>{{ $baris->pluck('nama')->implode(', ') }}</td></tr>
        <tr><td>Jumlah</td><td>{{ $baris->sum('jumlah') }}</td></tr>
    </table>
    <p>Bahwa Sarana/Prasarana yang disebutkan di atas mengalami kerusakan sebagai berikut:</p>
    <table class="t">
        <tr><th>Nama</th><th>Jumlah</th><th>Keterangan/Kerusakan</th></tr>
        @foreach ($baris as $b)
            <tr><td style="text-align:center">{{ $b['nama'] }}</td><td style="text-align:center">{{ $b['jumlah'] }}</td><td style="text-align:center">{{ $b['ket'] }}</td></tr>
        @endforeach
    </table>
    <p>Memerlukan perbaikan/pergantian.</p>
    <p>Demikian berita acara ini saya laporkan, atas perhatiannya terima kasih.</p>
    <p>{{ $kota }}, {{ $tglSurat }}</p>
    <table class="ttd">
        <tr>
            {!! $pemohonBlok !!}
            {!! $blok('Koordinator Sarana dan Prasarana', 'koordinator') !!}
        </tr>
    </table>
</div>

{{-- Halaman 3: Berita Acara Survey (diisi Koordinator SarPras) --}}
<div class="page">
    <h2>BERITA ACARA SURVEY KERUSAKAN SARANA DAN PRASARANA</h2>
    <table class="t">
        <tr><td style="width:30%">Jenis</td><td>Sarana/Prasarana</td></tr>
        <tr><td>Nama SarPras</td><td>{{ $baris->pluck('nama')->implode(', ') }}</td></tr>
        <tr><td>Jumlah</td><td>{{ $baris->sum('jumlah') }}</td></tr>
    </table>
    <p>Bahwa barang yang disebutkan di atas benar mengalami kerusakan, sehingga memerlukan perbaikan/pergantian.</p>
    <p>{{ $kota }}, {{ $tglSurat }}</p>
    <table class="ttd">
        <tr>
            {!! $blok('Koordinator Sarana dan Prasarana', 'koordinator') !!}
            {!! $blok('Mengetahui,<br>Kepala BAUK', 'kepala_bauk') !!}
        </tr>
        <tr>
            {!! $blok('Menyetujui,<br>Asisten Direktur II', 'asisten_direktur') !!}
            <td></td>
        </tr>
    </table>
</div>
</body>
</html>
