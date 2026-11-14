<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - SlowLiving Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/verifyOTP.css')}}">

</head>
<body>

    <!-- Verify Identity Card -->
    <div class="auth-card">
        <div class="text-center">
            <!-- Lock Icon -->
            <div class="icon-box">
                <i class="fa-solid fa-lock"></i>
            </div>
            
            <h2 class="mb-2" style="font-size: 1.75rem;">Verify your identity</h2>
            
            <p class="verify-desc">We sent a 6-digit code to:</p>
            <p class="verify-email">
                <strong>{{ session('user_email') }}</strong> 
                <a href="{{ url('/forgotton') }}" class="change-email-link">Change email</a>
            </p>
        </div>

        <form>
            
            <!-- OTP Inputs -->
            <div class="otp-container">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*" autofocus>
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            </div>
            
            <input type="hidden" class="session_email" value="{{ session('user_email') }}">
            <!-- Resend Row -->
            <div class="resend-row">
                <div class="resend-timer">
                    Resend code in <strong></strong>
                </div>
                <a href="#" class="resend-btn" id="resendBtn">Resend Code</a>
            </div>

            <!-- Verify Button -->
            <button type="button" class="btn btn-dark-custom btn-reset-link">
                Verify & Continue <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="js/verifyOtp.js"></script>
</body>
</html>