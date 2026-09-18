<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TSTH2</title>
    <style>
        body { 
            background-color: #0a6655; 
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
            border-radius: 4px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.3); 
            width: 100%; 
            max-width: 380px; 
            border-top: 5px solid #2ecc71;
        }
        .login-card h2 { 
            text-align: center; 
            color: #333; 
            margin-top: 0;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        .form-group label { 
            display: block; 
            margin-bottom: 5px; 
            color: #555; 
            font-size: 14px; 
            font-weight: bold;
        }
        .form-group input[type="email"], .form-group input[type="password"] { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ccc; 
            border-radius: 3px; 
            box-sizing: border-box; 
            font-size: 14px;
        }
        .form-group input[type="email"]:focus, .form-group input[type="password"]:focus {
            border-color: #0a6655;
            outline: none;
        }
        .btn-login { 
            width: 100%; 
            padding: 12px; 
            background-color: #0a6655; 
            color: white; 
            border: none; 
            border-radius: 3px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: bold;
            margin-top: 15px; 
        }
        .btn-login:hover { 
            background-color: #085244; 
        }
        .error { 
            color: #e74c3c; 
            font-size: 12px; 
            margin-top: 5px; 
            display: block;
        }
        .footer-links {
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            font-size: 13px;
            margin-top: 10px;
        }
        .footer-links a {
            color: #0a6655; 
            text-decoration: none;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
        .back-home {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
        }
        .back-home a {
            color: #888;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>TSTH2 SYSTEM LOGIN</h2>
        
        @if (session('status'))
            <div style="color: #27ae60; background: #e8f8f5; padding: 10px; border-radius: 3px; margin-bottom: 15px; text-align: center; font-size: 13px;">
                {{ session('status') }}
            </div>
        @endif

        <form novalidate method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email">
                @error('email')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required placeholder="Masukkan kata sandi">
                @error('password')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="footer-links">
                <label style="color: #666; cursor: pointer;">
                    <input type="checkbox" name="remember" style="margin-right: 5px;"> Remember Me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Lupa Sandi?</a>
                @endif
            </div>

            <button type="submit" class="btn-login">LOGIN</button>
        </form>

        <div class="back-home">
            <a href="{{ route('home') }}">&larr; Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
