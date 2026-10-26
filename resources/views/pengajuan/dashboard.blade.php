@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@php
    $rp = fn ($n) => 'Rp '.number_format($n, 0, ',', '.');
@endphp

<div class="top">
    <div>
        <h1>Dashboard</h1>
        <p class="muted" style="margin:0">{{ auth()->user()->unit ? 'Unit '.auth()->user()->unit : auth()->user()->label_peran }}</p>
    </div>
    <a href="{{ route('pengajuan.create') }}" class="btn blue">+ Pengajuan baru</a>
</div>

<div class="stats">
    <div class="card"><span>Total pengajuan</span><b>{{ $stat['total'] }}</b></div>
    <div class="card"><span>Bulan ini</span><b>{{ $stat['bulan_ini'] }}</b></div>
</div>

<div class="card scroll">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:8px">
        <h2 style="margin:0">Pengajuan terbaru</h2>
        <a class="link" href="{{ route('pengajuan.index') }}">Lihat semua &rarr;</a>
    </div>
    <table class="tbl">
        <tr><th>Nomor surat</th><th>Tanggal</th><th>Isi</th><th>Estimasi</th><th></th></tr>
        @forelse ($terbaru as $p)
            <tr>
                <td><b>{{ $p->nomor }}</b></td>
                <td>{{ $p->tanggal->translatedFormat('d M Y') }}</td>
                <td>{{ $p->items->pluck('nama_barang')->merge($p->perbaikan->pluck('nama_sarana'))->take(2)->implode(', ') }}</td>
                <td>{{ $rp($p->total) }}</td>
            </tr>
        @empty
            <tr><td colspan="5">Belum ada pengajuan. Klik "Pengajuan baru" untuk memulai.</td></tr>
        @endforelse
    </table>
</div>
@endsection
