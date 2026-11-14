<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmPasswordRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\OtpRequest;
use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    private function currentReset(): array
    {
        $user  = User::where('email', session('user_email'))->first();
        $reset = $user ? ResetPassword::where('user_id', $user->id)->first() : null;
        return [$user, $reset];
    }

    public function fetchRecord()
    {
        [, $reset] = $this->currentReset();

        if (!$reset || !$reset->send_link || now()->greaterThan($reset->expires_at)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['user' => $reset->only('send_link', 'otp_verified')]);
    }


    public function getResetLink(EmailRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        session(['user_email' => $request->email]);   

        if ($user) {
            try {
                $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                ResetPassword::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'token'        => Hash::make($otp),
                        'expires_at'   => now()->addMinutes(2),
                        'send_link'    => true,
                        'otp_verified' => false,    
                    ]
                );

                Mail::to($user->email)->send(new \App\Mail\resetpasswordmail($otp, $user->name));
            } catch (\Throwable $e) {
                report($e);
                return response()->json(['message' => 'Failed to send OTP email. Please try again.'], 500);
            }
        }

        return response()->json(['message' => 'If this email is registered, an OTP has been sent.'], 200);
    }


    public function verifyOtp(OtpRequest $request)
    {
        [$user, $reset] = $this->currentReset();

        if (!$reset || !$user || !$reset->token) {
            return response()->json(['message' => 'OTP not found. Please request a new OTP'], 404);
        }

        $key = 'otp-attempts:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['message' => 'Too many attempts. Request a new OTP later.'], 429);
        }

        if (now()->greaterThan($reset->expires_at)) {
            return response()->json(['message' => 'OTP code expired'], 410);
        }

        if (!Hash::check($request->code, $reset->token)) {
            RateLimiter::hit($key, 600);
            return response()->json(['message' => 'Invalid OTP code'], 401);
        }

        RateLimiter::clear($key);
        $reset->update(['otp_verified' => true, 'expires_at' => now()->addMinutes(10)]);

        return response()->json(['message' => 'OTP verified successfully']);
    }

    public function changePassword(ConfirmPasswordRequest $request)
    {
        [$user, $reset] = $this->currentReset();

        if (!$user || !$reset || !$reset->otp_verified || now()->greaterThan($reset->expires_at)) {
            return response()->json(['message' => 'OTP verification required'], 403);
        }

        $user->forceFill([
            'password'       => Hash::make($request->password),
            'remember_token' => Str::random(60),     // old "remember me" cookies stop working
        ])->save();
        $reset->delete();
        session()->forget('user_email');

        return response()->json(['message' => 'Your password was reset successfully'], 200);
    }
}
