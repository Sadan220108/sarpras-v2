@extends('layouts.app')
@section('title', $pengajuan ? 'Ubah pengajuan' : 'Pengajuan baru')
@section('content')
@php
    $edit = (bool) $pengajuan;
    $itemRows = old('items', $items);
    $repairRows = old('repairs', $repairs);
    $nextItem = $itemRows ? max(array_keys($itemRows)) + 1 : 0;
    $nextRepair = $repairRows ? max(array_keys($repairRows)) + 1 : 0;
@endphp

<div class="top">
    <div>
        <a href="{{ $edit ? route('pengajuan.show', $pengajuan) : route('dashboard') }}" class="link">&larr; Kembali</a>
        <h1 style="margin-top:6px">{{ $edit ? 'Ubah pengajuan '.$pengajuan->nomor : 'Pengajuan baru' }}</h1>
    </div>
</div>

@if ($errors->any())
    <div class="alert">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
@endif

<form method="POST" enctype="multipart/form-data" action="{{ $edit ? route('pengajuan.update', $pengajuan) : route('pengajuan.store') }}">
    @csrf
    @if ($edit) @method('PUT') @endif

    <div class="card">
        <dl class="dl" style="margin:0">
            <dt>Nama</dt><dd>{{ auth()->user()->name }}</dd>
            <dt>Jabatan</dt><dd>{{ auth()->user()->jabatan ?? '-' }}</dd>
            <dt>Unit bagian</dt><dd>{{ auth()->user()->unit ?? '-' }}</dd>
        </dl>
    </div>

    <div class="card">
        <h2>A. Pengajuan sarana baru</h2>
        <div id="rows-items">
            @foreach ($itemRows as $i => $v)
                @include('pengajuan._item', ['i' => $i, 'v' => $v])
            @endforeach
        </div>
        <button type="button" class="btn ghost" data-add="items">+ Tambah barang</button>
        <p class="muted" style="margin:10px 0 0">Wajib mengisi spesifikasi barang atau link marketplace.</p>
    </div>

    <div class="card">
        <h2>B. Pengajuan perbaikan sarana/prasarana</h2>
        <div id="rows-repairs">
            @foreach ($repairRows as $i => $v)
                @include('pengajuan._repair', ['i' => $i, 'v' => $v])
            @endforeach
        </div>
        <button type="button" class="btn ghost" data-add="repairs">+ Tambah perbaikan</button>
    </div>

    <div class="card">
        <h2>Lampiran (opsional)</h2>
        @if ($edit && $pengajuan->lampiran->count())
            <p class="muted" style="margin:0 0 6px">Berkas yang sudah diunggah (centang untuk menghapus):</p>
            @foreach ($pengajuan->lampiran as $l)
                <label style="display:block;margin-bottom:4px"><input type="checkbox" name="hapus_lampiran[]" value="{{ $l->id }}"> {{ $l->nama_asli }}</label>
            @endforeach
        @endif
        <label class="f" style="margin-top:8px"><span>Tambah berkas (PDF, JPG, atau PNG, maksimal 5 berkas @ 2 MB)</span>
            <input type="file" name="lampiran[]" multiple accept=".pdf,.jpg,.jpeg,.png" style="padding-top:8px">
        </label>
    </div>

    <div class="total">Total estimasi: <span id="total">Rp 0</span></div>
    <div style="text-align:right"><button type="submit" class="btn blue">{{ $edit ? 'Simpan perubahan' : 'Simpan pengajuan' }}</button></div>
</form>

<template id="tpl-items">@include('pengajuan._item', ['i' => '__i__', 'v' => []])</template>
<template id="tpl-repairs">@include('pengajuan._repair', ['i' => '__i__', 'v' => []])</template>

<script>
(function () {
    var next = { items: {{ $nextItem }}, repairs: {{ $nextRepair }} };

    function hitung() {
        var total = 0;
        document.querySelectorAll('#rows-items [data-row]').forEach(function (r) {
            total += (+r.querySelector('.js-biaya').value || 0) * (+r.querySelector('.js-jumlah').value || 0);
        });
        document.querySelectorAll('#rows-repairs [data-row]').forEach(function (r) {
            total += (+r.querySelector('.js-biaya').value || 0);
        });
        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.querySelectorAll('[data-add]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var t = btn.dataset.add;
            var html = document.getElementById('tpl-' + t).innerHTML.split('__i__').join(next[t]++);
            document.getElementById('rows-' + t).insertAdjacentHTML('beforeend', html);
            hitung();
        });
    });

    document.addEventListener('click', function (e) {
        if (e.target.matches('[data-remove]')) {
            e.target.closest('[data-row]').remove();
            hitung();
        }
    });
    document.addEventListener('input', hitung);
    hitung();
})();
</script>
@endsection
