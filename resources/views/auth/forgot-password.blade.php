<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Sandi - TSTH2</title>
    <style>
        body { 
            background-color: #054439;
            font-family: Arial, Helvetica, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .login-card { 
            background: white; 
            padding: 40px; 
            border-radius: 8px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.3); 
            width: 100%; 
            max-width: 400px; 
            border-top: 5px solid #c09e5c;
        }
        .login-card h2 { 
            text-align: center; 
            color: #333; 
            margin-top: 0;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .login-card p {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
            text-align: center;
            line-height: 1.5;
        }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; color: #555; font-size: 14px; font-weight: bold; }
        .form-group input[type="email"] { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        .form-group input[type="email"]:focus { border-color: #054439; outline: none; box-shadow: 0 0 0 2px rgba(5,68,57,0.2); }
        .btn-login { width: 100%; padding: 14px; background-color: #054439; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 15px; font-weight: bold; margin-top: 5px; transition: background 0.3s; }
        .btn-login:hover { background-color: #02231d; }
        .error { color: #e74c3c; font-size: 12px; margin-top: 5px; display: block; }
        .back-home { text-align: center; margin-top: 25px; font-size: 14px; }
        .back-home a { color: #888; text-decoration: none; }
        .back-home a:hover { color: #054439; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>LUPA SANDI</h2>
        <p>Masukkan alamat email Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
        
        @if (session('status'))
            <div style="color: #15803d; background: #dcfce7; padding: 12px; border-radius: 4px; margin-bottom: 20px; text-align: center; font-size: 14px; font-weight: bold;">
                {{ session('status') }}
            </div>
        @endif

        <form novalidate method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email terdaftar">
                @error('email')<span class="error">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn-login">KIRIM LINK RESET</button>
        </form>

        <div class="back-home">
            <a href="{{ route('login') }}">&larr; Kembali ke Login</a>
        </div>
    </div>
</body>
</html>
