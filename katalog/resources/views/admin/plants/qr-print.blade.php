<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code {{ $plant->name }} | TSTH2</title>
    <style>
        body { align-items: center; color: #003f32; display: flex; font-family: Arial, sans-serif; justify-content: center; min-height: 100vh; text-align: center; }
        img { display: block; margin: 20px auto; max-width: min(80vw, 600px); width: 100%; }
        h1 { font-size: 24px; margin: 0; }
        @media print { @page { margin: 1cm; } }
    </style>
</head>
<body onload="window.print()">
    <main>
        <h1>{{ $plant->name }}</h1>
        <img src="{{ $qrUrl }}" alt="QR Code {{ $plant->name }}">
        <p>Scan untuk membuka detail tanaman TSTH2</p>
    </main>
</body>
</html>
