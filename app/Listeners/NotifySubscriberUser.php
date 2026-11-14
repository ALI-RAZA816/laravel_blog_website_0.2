<?php

namespace App\Listeners;

use App\Events\SubscribedUser;
use App\Models\NewsLetter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NotifySubscriberUser implements ShouldQueue
{
    public function handle(SubscribedUser $event): void
    {
        NewsLetter::whereNotNull('confirmed_at')->each(function ($subscriber) use ($event) {
            Mail::to($subscriber->email)->send(new \App\Mail\publishedNewsMail($subscriber, $event->post));
        });
    }
}