<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FallyGuard - Verify Email</title>
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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <img src="{{ $logo }}" alt="FallyGuard Logo" style="width: 100px; height: 100px;">
            </div>
            <div class="col-md-12">
                <h2>Verify Your Email Address</h2>
                {{-- OTP Verification --}}
                <p>Hi, {{ $user->name }}</p>
                <p>Thank you for creating an account with us. Don't forget to verify your email address.</p>
                <p>Use the following OTP to verify your email address: <strong
                        class="otp">{{ $otp }}</strong></p>
                <p>If you did not create an account, no further action is required.</p>


                <p>Regards,</p>
                <p>FallyGuard Team</p>
            </div>
        </div>
    </div>
</body>

</html>
