<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Pengajuan SarPras</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/tema-nsc.css') }}?v={{ @filemtime(public_path('css/tema-nsc.css')) }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo-kampus.png') }}">
</head>
<body>
@php $u = auth()->user(); @endphp
<div class="shell">
    <header class="side">
        <div class="brand">
            <span class="mark"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"/></svg></span>
            <span>SarPras<small>Pengajuan Sarana dan Prasarana</small></span>
        </div>
        <nav>
            @if ($u->role === 'admin')
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.*') ? 'on' : '' }}"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/></svg>Pengguna</a>
            @else
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'on' : '' }}"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>Dashboard</a>
                <a href="{{ route('pengajuan.index') }}" class="{{ request()->routeIs('pengajuan.index', 'pengajuan.show', 'pengajuan.edit') ? 'on' : '' }}"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM14 2v6h6M8 13h8M8 17h8"/></svg>Pengajuan Saya</a>
                <a href="{{ route('pengajuan.create') }}" class="{{ request()->routeIs('pengajuan.create') ? 'on' : '' }}"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/></svg>Pengajuan Baru</a>
            @endif
        </nav>
        <div class="me">
            <span class="ava">{{ strtoupper(mb_substr($u->name, 0, 1)) }}</span>
            <span class="role">{{ $u->label_peran }}</span>
            <b>{{ $u->name }}</b>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Keluar</button>
            </form>
        </div>
    </header>

    <main class="main">
        @if (session('sukses'))
            <div class="flash">{{ session('sukses') }}</div>
        @endif
        @yield('content')
    </main>
</div>
<script>
document.addEventListener('click',function(e){
    var b=e.target.closest('.btn'); if(!b) return;
    var r=b.getBoundingClientRect(), d=Math.max(r.width,r.height)*2.2, s=document.createElement('span');
    s.className='ripple';
    s.style.cssText='width:'+d+'px;height:'+d+'px;left:'+(e.clientX-r.left-d/2)+'px;top:'+(e.clientY-r.top-d/2)+'px';
    b.appendChild(s); setTimeout(function(){s.remove()},750);
});
</script>
</body>
</html>
