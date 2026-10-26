@extends('layouts.app')
@section('title', $user->exists ? 'Ubah akun' : 'Tambah akun')
@section('content')
@php $sendiri = $user->exists && $user->id === auth()->id(); @endphp
<div class="top">
    <div>
        <a href="{{ route('admin.users.index') }}" class="link">&larr; Kembali</a>
        <h1 style="margin-top:6px">{{ $user->exists ? 'Ubah akun' : 'Tambah akun' }}</h1>
    </div>
</div>

@if ($errors->any())
    <div class="alert">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
@endif

<form method="POST" class="card" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
    @csrf
    @if ($user->exists) @method('PUT') @endif
    <div class="g" style="grid-template-columns:repeat(auto-fit,minmax(260px,1fr))">
        <label class="f"><span>Nama</span><input name="name" value="{{ old('name', $user->name) }}"></label>
        <label class="f"><span>Email</span><input type="email" name="email" value="{{ old('email', $user->email) }}"></label>
        <label class="f"><span>Peran</span>
            <select class="in" name="role" @if ($sendiri) disabled @endif>
                @foreach ($roles as $k => $v)
                    <option value="{{ $k }}" @selected(old('role', $user->role ?: 'pemohon') === $k)>{{ $v }}</option>
                @endforeach
            </select>
            @if ($sendiri)<small>Admin tidak bisa mengubah perannya sendiri.</small>@endif
        </label>
        <label class="f"><span>Jabatan (tampil di surat)</span><input name="jabatan" value="{{ old('jabatan', $user->jabatan) }}"></label>
        <label class="f"><span>Unit bagian (tampil di surat)</span><input name="unit" value="{{ old('unit', $user->unit) }}"></label>
        <label class="f"><span>{{ $user->exists ? 'Password baru (kosongkan jika tidak diubah)' : 'Password (minimal 8 karakter)' }}</span><input type="password" name="password" autocomplete="new-password"></label>
    </div>
    @if ($sendiri)<input type="hidden" name="role" value="{{ $user->role }}">@endif
    <div style="margin-top:18px"><button type="submit" class="btn blue">Simpan</button></div>
</form>
@endsection
