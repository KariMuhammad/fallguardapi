{{-- Reset Password --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
</head>

<body>
    <h3>Hi {{ user->$name }},</h3>
    <h1>Reset Password</h1>
    <p>Use the following OTP code to reset your password: <strong>{{ $otp }}</strong></p>

    <p>Thank you</p>

    <p>{{ config('app.name') }} Team</p>

    <p>We received a request to reset your password. If you did not make this request, simply ignore this email. </p>
</body>

</html>
