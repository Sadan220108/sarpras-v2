<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Pengajuan SarPras</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@php $u = auth()->user(); @endphp
<div class="shell">
    <aside class="side">
        <div class="brand">SarPras<small>Pengajuan Sarana dan Prasarana</small></div>
        <nav>
            @if ($u->role === 'admin')
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.*') ? 'on' : '' }}">Pengguna</a>
            @else
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'on' : '' }}">Dashboard</a>
                <a href="{{ route('pengajuan.index') }}" class="{{ request()->routeIs('pengajuan.index', 'pengajuan.show', 'pengajuan.edit') ? 'on' : '' }}">Pengajuan Saya</a>
                <a href="{{ route('pengajuan.create') }}" class="{{ request()->routeIs('pengajuan.create') ? 'on' : '' }}">+ Pengajuan Baru</a>
            @endif
        </nav>
        <div class="me">
            {{ $u->label_peran }}
            <b>{{ $u->name }}</b>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Keluar</button>
            </form>
        </div>
    </aside>

    <main class="main">
        @if (session('sukses'))
            <div class="flash">{{ session('sukses') }}</div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>
