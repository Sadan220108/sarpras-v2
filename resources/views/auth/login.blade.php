<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Pengajuan SarPras</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="card login">
    <h1 style="font-size:24px">Pengajuan SarPras</h1>
    <p class="muted" style="margin:0 0 18px">Masuk untuk mengajukan atau memproses pengajuan sarana dan prasarana.</p>
    <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <label class="f"><span>Email</span><input type="email" name="email" value="{{ old('email') }}" autofocus></label>
        @error('email')<div class="alert">{{ $message }}</div>@enderror
        <label class="f"><span>Password</span><input type="password" name="password"></label>
        @error('password')<div class="alert">{{ $message }}</div>@enderror
        <label style="display:flex;gap:8px;align-items:center;margin-bottom:16px"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
        <button type="submit" class="btn" style="width:100%">Masuk</button>
    </form>
</div>
</body>
</html>
