<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - TSTH2</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .form-container {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            max-width: 600px;
            margin-bottom: 2rem;
        }
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--color-text-dark); }
        .form-input { width: 100%; padding: 0.8rem 1.2rem; border: 2px solid #e2e8f0; border-radius: 8px; font-family: var(--font-body); font-size: 1rem; }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo">
                <span>TSTH2 Panel</span>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item">
                    <span class="nav-icon">📊</span>
                    Dashboard Utama
                </a>
                
                @if(in_array(auth()->user()->role, ['pic_warehouse', 'warehouse', 'agronom']))
                <a href="{{ route('warehouse') }}" class="nav-item">
                    <span class="nav-icon">🌿</span>
                    Data Tanaman
                </a>
                <a href="#" class="nav-item">
                    <span class="nav-icon">📦</span>
                    Inventory
                </a>
                @endif

                @if(auth()->user()->role === 'web_admin')
                <a href="{{ route('web.management') }}" class="nav-item">
                    <span class="nav-icon">🌐</span>
                    Manajemen Web
                </a>
                <a href="#" class="nav-item">
                    <span class="nav-icon">📰</span>
                    Berita & Artikel
                </a>
                @endif
                
                <a href="{{ route('home') }}" class="nav-item" style="margin-top: auto; position: absolute; bottom: 2rem; width: calc(100% - 2rem);">
                    <span class="nav-icon">🔙</span>
                    Kembali ke Web
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <div></div>
                <div class="user-profile" style="display: flex; align-items: center; gap: 1rem;">
                    <div style="text-align: right;">
                        <span style="font-weight: 500; font-size: 0.9rem; display: block;">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                </div>
            </header>

            <div class="dashboard-body">
                <div class="page-title">
                    <h1>Pengaturan Profil</h1>
                    <p>Perbarui informasi akun dan kata sandi Anda.</p>
                </div>

                @if(session('status') === 'profile-updated' || session('status') === 'password-updated')
                <div style="background-color: rgba(46, 204, 113, 0.1); color: var(--color-success); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    Profil berhasil diperbarui.
                </div>
                @endif

                <!-- Update Profile Info -->
                <div class="form-container">
                    <h3 style="margin-bottom: 1.5rem;">Informasi Akun</h3>
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')
                        
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Informasi</button>
                    </form>
                </div>

                <!-- Update Password -->
                <div class="form-container">
                    <h3 style="margin-bottom: 1.5rem;">Ubah Kata Sandi</h3>
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        
                        <div class="form-group">
                            <label class="form-label">Sandi Saat Ini</label>
                            <input type="password" name="current_password" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sandi Baru</label>
                            <input type="password" name="password" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Sandi Baru</label>
                            <input type="password" name="password_confirmation" class="form-input" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Perbarui Sandi</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
