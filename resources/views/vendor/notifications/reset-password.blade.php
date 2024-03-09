<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        .otp {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            background-color: #f2f2f2;
            padding: 1rem;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h3>Hi {{ $user->name }},</h3>
    <h1>Reset Password</h1>
    <p>Use the following OTP code to reset your password: <strong class="otp">{{ $otp }}</strong></p>

    <p>Thank you</p>

    <p>{{ config('app.name') }} Team</p>

    <p>We received a request to reset your password. If you did not make this request, simply ignore this email. </p>
</body>

</html>
