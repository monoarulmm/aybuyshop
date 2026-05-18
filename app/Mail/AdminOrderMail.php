<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'নতুন অর্ডার অ্যালার্ট - #' . $this->order->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_order_notification', // নিশ্চিত করুন এই পাথটি সঠিক
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
