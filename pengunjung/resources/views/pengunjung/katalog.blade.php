@extends('layouts.public')

@section('title', 'Katalog Tanaman - TSTH2')

@section('hero_title', 'Katalog Tanaman')
@section('hero_desc', 'Ensiklopedia Flora TSTH2 — Koleksi plasma nutfah herbal terlengkap.')

@section('content')
<div class="container">
    <style>
        .coming-soon-container {
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
        }
        .icon-wip {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }
    </style>
    <div class="coming-soon-container">
        <div class="icon-wip" style="color: var(--color-primary);"><span class="material-symbols-rounded" style="font-size: 5rem;">potted_plant</span></div>
        <h2 style="font-size: 2.5rem; color: var(--color-primary-dark); margin-bottom: 1rem; font-family: var(--font-heading);">Halaman Dalam Pengembangan</h2>
        <p style="font-size: 1.1rem; color: var(--color-text-muted); max-width: 600px; line-height: 1.6;">
            Katalog Tanaman TSTH2 (Ensiklopedia Flora) sedang dalam tahap pembangunan sistem. Nantikan koleksi data plasma nutfah herbal terlengkap segera hadir di sini!
        </p>
        <a href="{{ route('katalog') }}" class="btn btn-primary" style="margin-top: 2.5rem; padding: 1rem 2rem; font-size: 1.1rem; border-radius: 999px; box-shadow: 0 10px 20px rgba(10, 102, 85, 0.2);">Lihat Katalog Tanaman</a>
    </div>
</div>
@endsection
