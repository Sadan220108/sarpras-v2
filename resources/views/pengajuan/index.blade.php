@extends('layouts.app')
@section('title', 'Pengajuan Saya')
@section('content')
@php $rp = fn ($n) => 'Rp '.number_format($n, 0, ',', '.'); @endphp

<div class="top">
    <h1>Pengajuan Saya</h1>
    <a href="{{ route('pengajuan.create') }}" class="btn blue">+ Pengajuan baru</a>
</div>

<form method="GET" action="{{ route('pengajuan.index') }}" class="bar">
    <input type="search" name="q" value="{{ $q }}" placeholder="Cari nomor surat atau nama barang" aria-label="Cari pengajuan">
    <button type="submit" class="btn">Cari</button>
</form>

<div class="card scroll">
    <table class="tbl">
        <tr><th>Nomor surat</th><th>Tanggal</th><th>Isi</th><th>Estimasi</th><th></th></tr>
        @forelse ($list as $p)
            <tr class="hov">
                <td><b>{{ $p->nomor }}</b></td>
                <td>{{ $p->tanggal->translatedFormat('d M Y') }}</td>
                <td>{{ $p->items->pluck('nama_barang')->merge($p->perbaikan->pluck('nama_sarana'))->take(2)->implode(', ') }}</td>
                <td>{{ $rp($p->total) }}</td>
                <td style="white-space:nowrap">
                    <a class="link" href="{{ route('pengajuan.show', $p) }}">Buka</a> &middot;
                </td>
            </tr>
        @empty
            <tr><td colspan="5">Tidak ada pengajuan yang cocok.</td></tr>
        @endforelse
    </table>
</div>

<div class="pager">
    <span>Halaman {{ $list->currentPage() }} dari {{ $list->lastPage() }}</span>
    <span style="display:flex;gap:8px">
        @if (! $list->onFirstPage())<a href="{{ $list->previousPageUrl() }}">&larr; Sebelumnya</a>@endif
        @if ($list->hasMorePages())<a href="{{ $list->nextPageUrl() }}">Berikutnya &rarr;</a>@endif
    </span>
</div>
@endsection
