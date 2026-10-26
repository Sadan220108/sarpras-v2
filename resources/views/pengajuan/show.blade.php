@extends('layouts.app')
@section('title', $pengajuan->nomor)
@section('content')
@php $rp = fn ($n) => 'Rp '.number_format($n, 0, ',', '.'); @endphp

<div class="top">
    <div>
        <a href="{{ route('pengajuan.index') }}" class="link">&larr; Kembali</a>
        <h1 style="margin-top:6px">{{ $pengajuan->nomor }}</h1>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a href="{{ route('pengajuan.edit', $pengajuan) }}" class="btn ghost">Ubah</a>
        <a href="{{ route('pengajuan.cetak', $pengajuan) }}" target="_blank" class="btn blue">Cetak surat dan berita acara</a>
    </div>
</div>

<div class="card">
    <dl class="dl" style="margin:0">
        <dt>Pemohon</dt><dd>{{ $pengajuan->pemohon->name }}</dd>
        <dt>Jabatan / unit</dt><dd>{{ $pengajuan->jabatan ?? '-' }} / {{ $pengajuan->unit ?? '-' }}</dd>
        <dt>Tanggal</dt><dd>{{ $pengajuan->tanggal->translatedFormat('j F Y') }}</dd>
        <dt>Total estimasi</dt><dd><b>{{ $rp($pengajuan->total) }}</b></dd>
    </dl>
</div>

@if ($pengajuan->items->count())
    <div class="card scroll">
        <h2>A. Sarana baru</h2>
        <table class="tbl">
            <tr><th>Nama barang</th><th>Spesifikasi</th><th>Biaya/satuan</th><th>Jumlah</th><th>Subtotal</th><th>Keterangan</th></tr>
            @foreach ($pengajuan->items as $i)
                <tr>
                    <td>{{ $i->nama_barang }}</td>
                    <td>{{ $i->spesifikasi }}@if ($i->link && \Illuminate\Support\Str::startsWith($i->link, ['http://', 'https://']))<div><a class="link" href="{{ $i->link }}" target="_blank" rel="noopener noreferrer">Buka link</a></div>@endif</td>
                    <td>{{ $rp($i->biaya) }}</td>
                    <td>{{ $i->jumlah }}</td>
                    <td>{{ $rp($i->biaya * $i->jumlah) }}</td>
                    <td>{{ $i->keterangan }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endif

@if ($pengajuan->perbaikan->count())
    <div class="card scroll">
        <h2>B. Perbaikan</h2>
        <table class="tbl">
            <tr><th>Sarana/prasarana</th><th>Kerusakan</th><th>Lokasi</th><th>Estimasi</th><th>Keterangan</th></tr>
            @foreach ($pengajuan->perbaikan as $r)
                <tr><td>{{ $r->nama_sarana }}</td><td>{{ $r->kerusakan }}</td><td>{{ $r->lokasi }}</td><td>{{ $rp($r->biaya) }}</td><td>{{ $r->keterangan }}</td></tr>
            @endforeach
        </table>
    </div>
@endif

@if ($pengajuan->lampiran->count())
    <div class="card">
        <h2>Lampiran</h2>
        @foreach ($pengajuan->lampiran as $l)
            <div><a class="link" href="{{ route('lampiran.unduh', $l) }}">{{ $l->nama_asli }}</a></div>
        @endforeach
    </div>
@endif
@endsection
