<?php

namespace App\Listeners;

use App\Events\ReceiveMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendMessage implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReceiveMessage $event): void
    {
        foreach($event->useremail as $email){
            Mail::to($email)->send(new \App\Mail\sendMessageMail($event->name, $event->email, $event->subject, $event->message));
        }
    }
}
