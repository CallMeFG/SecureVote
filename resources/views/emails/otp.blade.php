<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode OTP Anda</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .header {
            background-color: #1a56db;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
            color: #374151;
            line-height: 1.6;
        }
        .content p {
            margin-bottom: 15px;
        }
        .otp-box {
            background-color: #f3f4f6;
            border: 1px dashed #d1d5db;
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            margin: 25px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 5px;
            color: #1a56db;
            margin: 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 15px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .alert {
            color: #dc2626;
            font-size: 14px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SecureVote PCR</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            <p>Anda menerima email ini karena ada permintaan login atau pendaftaran akun pada sistem pemilihan SecureVote.</p>
            <p>Berikut adalah kode OTP Anda. Kode ini berlaku selama <strong>5 menit</strong>.</p>
            
            <div class="otp-box">
                <p class="otp-code">{{ $token }}</p>
            </div>
            
            <p class="alert">JANGAN BERIKAN KODE INI KEPADA SIAPAPUN, termasuk panitia pemilihan.</p>
            <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini karena akun Anda tetap aman.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} SecureVote - Politeknik Caltex Riau. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
