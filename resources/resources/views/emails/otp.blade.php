<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        .container {
            background: #ffffff;
            max-width: 480px;
            margin: 30px auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid #ddd;
        }
        .header {
            background-color: #4F46E5;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 20px;
        }
        .content {
            padding: 25px;
            text-align: center;
        }
        .otp {
            display: inline-block;
            background-color: #f0f0f5;
            color: #111827;
            font-size: 24px;
            letter-spacing: 4px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            color: #6b7280;
            text-align: center;
            padding: 15px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Your Login OTP</div>
        <div class="content">
            <p>Hello {{ $user->name ?? 'User' }},</p>
            <p>Use the following One-Time Password (OTP) to log in:</p>
            <div class="otp">{{ $otp }}</div>
            <p>This OTP is valid for the next 10 minutes. Please do not share it with anyone.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} RMS. All rights reserved.
        </div>
    </div>
</body>
</html>
