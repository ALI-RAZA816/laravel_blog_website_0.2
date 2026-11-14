<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterConfirmMail;
use App\Models\NewsLetter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class NewsLetterController extends Controller
{
    public function Subscribed(Request $request)
    {
        $request->validate([
            'newsletter' => 'required|email|max:255',
        ], [
            'newsletter.required' => 'Email is required',
            'newsletter.email'    => 'Email must be valid',
        ]);

        $email = Str::lower(trim($request->newsletter));

        $reply = response()->json([
            'message' => 'If this email is register, a confirmation link sent to your email. Please check your inbox or spam folder.',
        ]);

        $user = User::where('email', $email)->first();
        if (!$user) return $reply;

        $subscriber = NewsLetter::firstOrCreate(['email' => $email], ['user_id' => $user->id]);
        if ($subscriber->confirmed_at) return $reply;   

        try {
            RateLimiter::attempt('newsletter-confirm:' . sha1($email), 1, function () use ($email, $subscriber) {
                Mail::to($email)->send(new NewsletterConfirmMail($subscriber));
            }, 300);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'We could not send the confirmation email. Pelase try again later'], 500);
        }

        return $reply;
    }

    public function confirm(int $id)
    {
        $subscriber = NewsLetter::find($id);

        if (!$subscriber) {
            return view('pages.newsletter-status', [
                'title' => 'Invalid Link',
                'message' => 'This link is expired or the subscription does not exit. Please try again',
            ]);
        }

        if (!$subscriber->confirmed_at) {
            $subscriber->update(['confirmed_at' => now()]);
        }

        return view('pages.newsletter-status', [
            'title' => 'Subscription confirmed',
            'message' => 'Than you! You will now receive an email whenever a new post is published ',
        ]);
    }

    public function unsubscribe(int $id)
    {
        NewsLetter::where('id', $id)->delete();

        return view('pages.newsletter-status', [
            'title' => 'You have been unsubscribed',
            'message' => 'You will no longer receive our newsletter',
        ]);
    }
}