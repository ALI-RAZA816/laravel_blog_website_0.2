<?php

namespace App\Mail;

use App\Models\NewsLetter;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class publishedNewsMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $emails;
    public string $unsubscribeUrl;

    public function __construct(public NewsLetter $subscriber, public Post $post)
    {
        $this->emails = $subscriber->email;
        $this->unsubscribeUrl = URL::signedRoute('newsletter.unsubscribe', ['id' => $subscriber->id]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New story: ' . $this->post->title);
    }

    public function headers(): Headers
    {
        // Gmail/Outlook mein "Unsubscribe" ka button dikhane ke liye
        return new Headers(text: ['List-Unsubscribe' => '<' . $this->unsubscribeUrl . '>']);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.published');
    }
}