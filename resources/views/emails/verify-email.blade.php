<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 150px;
        }

        .button {
            display: inline-block;
            padding: 12px 25px;
            color: #ffffff;
            background-color: #1a1a1a;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }

        p {
            color: #333;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="logo">
            <img src="{{ $appLogo }}" alt="{{ $appName }}">
        </div>
        <p>Hello,</p>
        <p>
            To complete your registration on <strong>Wheels</strong>, please click the button below to confirm your
            email address.
        </p>
        <p>
            <a href="{{ $url }}" class="button" style="color: #ffffff !important; text-decoration: none;">Confirm
                Email</a>
        </p>
        <p>If you did not create an account, you can safely ignore this email.</p>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
        </div>
    </div>
</body>

</html>
