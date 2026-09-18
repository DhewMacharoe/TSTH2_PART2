@extends('layouts.dashboard')

@section('title', 'Logbook Karyawan - TSTH2')

@section('content')
<div class="page-title">
    <h1>Logbook Karyawan</h1>
    <p>Catatan kegiatan harian karyawan TSTH2</p>
</div>

<div style="background: white; border-radius: 16px; padding: 4rem; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.02); margin-top: 2rem;">
    <div style="font-size: 4rem; margin-bottom: 1rem; animation: bounce 2s infinite;">🚧</div>
    <h2 style="font-family: var(--font-heading); color: var(--color-text-dark); margin-bottom: 1rem;">Halaman Dalam Pengembangan</h2>
    <p style="color: var(--color-text-muted); max-width: 500px; margin: 0 auto; line-height: 1.6;">
        Fitur Logbook Karyawan saat ini sedang dalam tahap pengembangan. Fitur ini akan memungkinkan karyawan untuk mencatat dan melaporkan kegiatan harian mereka.
    </p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary" style="margin-top: 2rem;">Kembali ke Dashboard Utama</a>
</div>

<style>
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(-20px);}
        60% {transform: translateY(-10px);}
    }
</style>
@endsection
