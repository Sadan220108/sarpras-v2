@extends('layouts.app')
@section('title', 'Pengguna')
@section('content')
<div class="top">
    <h1>Pengguna</h1>
    <a href="{{ route('admin.users.create') }}" class="btn blue">+ Tambah akun</a>
</div>
<div class="card scroll">
    <table class="tbl">
        <tr><th>Nama</th><th>Email</th><th>Peran</th><th>Jabatan</th><th>Unit</th><th></th></tr>
        @foreach ($users as $u)
            <tr class="hov">
                <td><b>{{ $u->name }}</b></td>
                <td>{{ $u->email }}</td>
                <td>{{ $roles[$u->role] ?? $u->role }}</td>
                <td>{{ $u->jabatan }}</td>
                <td>{{ $u->unit }}</td>
                <td><a class="link" href="{{ route('admin.users.edit', $u) }}">Ubah</a></td>
            </tr>
        @endforeach
    </table>
</div>
@endsection
