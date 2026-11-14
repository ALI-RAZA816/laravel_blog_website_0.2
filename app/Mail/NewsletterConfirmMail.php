<?php

namespace App\Mail;

use App\Models\NewsLetter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class NewsletterConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $confirmUrl;

    public function __construct(public NewsLetter $subscriber)
    {
        $this->confirmUrl = URL::temporarySignedRoute(
            'newsletter.confirm', now()->addDay(), ['id' => $subscriber->id]
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Confirm your newsletter subscription');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.newsletter-confirm');
    }
}