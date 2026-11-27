<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Masuk — NgajiKu</title>
<link rel="stylesheet" href="assets/style.css">
<script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

<div class="auth">

<a class="back" href="index.html">
<i data-lucide="arrow-left"></i>Kembali ke halaman utama
</a>

<h1>Masuk ke Akun</h1>
<p class="lead">Gunakan email dan kata sandi yang terdaftar.</p>

<form id="f" action="dashboard_ustadz.html">

<div class="field">
<label>Email</label>
<input type="email" id="em" required value="ustadz@ngajiku.id">
</div>

<div class="field">
<label>Kata Sandi</label>
<div class="pw">
<input type="password" id="pw" required value="ngaji123">
<button type="button" onclick="pw.type=pw.type==='password'?'text':'password'" aria-label="Tampilkan sandi">
<i data-lucide="eye"></i>
</button>
</div>
</div>

<button class="btn btn-primary btn-block">
<i data-lucide="log-in"></i>Masuk
</button>

</form>

<div class="demo">
<b>Akun demo untuk dicoba:</b>
<p>Ustadz: <code>ustadz@ngajiku.id</code> / sandi: <code>ngaji123</code></p>
<p>Orang Tua: <code>ortu@ngajiku.id</code> / sandi: <code>ngaji123</code></p>
<p>Murid: <code>murid@ngajiku.id</code> / sandi: <code>ngaji123</code></p>
</div>

</div>

<script>
lucide.createIcons();

document.getElementById('f').onsubmit=e=>{
e.preventDefault();
const m=document.getElementById('em').value;
location.href=m.startsWith('ortu')
?'dashboard_ortu.html'
:m.startsWith('murid')
?'dashboard_murid.html'
:'dashboard_ustadz.html'
}
</script>

</body>
</html>