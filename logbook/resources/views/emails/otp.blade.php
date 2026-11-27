<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Logbook Karyawan</title>
</head>
<body style="margin:0; padding:30px 10px; background:#f3f4f6; font-family:Arial,sans-serif;">

    <div style="max-width:520px; margin:auto; background:white; border-radius:12px; padding:30px;">

        <h2 style="color:#166534; text-align:center;">
            Logbook Karyawan
        </h2>

        <p>Halo,</p>

        <p>
            Kami menerima permintaan <strong>{{ $purposeText }}</strong>
            pada akun Logbook Karyawan kamu.
        </p>

        <p>Gunakan kode OTP berikut untuk melanjutkan proses:</p>

        <div style="background:#f0fdf4; border:1px dashed #16a34a;
                    border-radius:10px; padding:20px; text-align:center;
                    margin:25px 0;">

            <h1 style="font-size:32px; letter-spacing:8px;
                       color:#166534; margin:0;">
                {{ $otp }}
            </h1>

        </div>

        <p>
            Kode ini hanya berlaku selama <strong>5 menit</strong>
            dan hanya dapat digunakan satu kali.
        </p>

        <p style="color:#dc2626;">
            Jangan berikan kode OTP ini kepada siapa pun,
            termasuk pihak yang mengaku sebagai administrator.
        </p>

        <p>
            Jika kamu tidak merasa melakukan permintaan ini,
            abaikan email tersebut.
        </p>

        <hr style="border:none; border-top:1px solid #e5e7eb; margin:25px 0;">

        <p style="font-size:12px; color:#6b7280; text-align:center;">
            Email otomatis dari Sistem Logbook Karyawan.<br>
            Mohon tidak membalas email ini.
        </p>

    </div>

</body>
</html>
