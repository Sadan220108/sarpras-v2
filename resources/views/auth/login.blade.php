<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Pengajuan SarPras</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/tema-nsc.css') }}?v={{ @filemtime(public_path('css/tema-nsc.css')) }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo-kampus.png') }}">
</head>
<body>
<div class="auth">
    <section class="auth-art">
        <div class="brand">
            <span class="mark"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V8l7-5 7 5v13M9 21v-6h6v6"/></svg></span>
            <span>SarPras<small>Sarana dan Prasarana</small></span>
        </div>
        <div>
            <h2>Ajukan kebutuhan sarana dan prasarana dengan lebih rapi.</h2>
            <p>Isi pengajuan, pantau statusnya, dan cetak surat serta berita acara dalam satu tempat.</p>
        </div>
        <small style="color:rgba(255,255,255,.6)">&copy; {{ date('Y') }} SarPras</small>
    </section>
    <section class="auth-form">
        <div class="login">
            <h1>Selamat datang</h1>
            <p class="muted" style="margin:0 0 22px">Masuk untuk mengajukan sarana dan prasarana.</p>
            @error('email')<div class="alert">{{ $message }}</div>@enderror
            @error('password')<div class="alert">{{ $message }}</div>@enderror
            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <label class="f"><span>Email</span><input type="email" name="email" value="{{ old('email') }}" autofocus></label>
                <label class="f"><span>Password</span><input type="password" name="password"></label>
                <label style="display:flex;gap:8px;align-items:center;margin-bottom:16px"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
                <button type="submit" class="btn blue" style="width:100%;margin-top:4px">Masuk</button>
            </form>
        </div>
    </section>
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
