<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewWithdrawReq extends Notification
{
    use Queueable;

    public $user;
    public $withdrawal;

    // ডাটা রিসিভ করার জন্য কনস্ট্রাক্টর
    public function __construct($user, $withdrawal)
    {
        $this->user = $user;
        $this->withdrawal = $withdrawal;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('নতুন উইথড্র রিকোয়েস্ট এসেছে!')
            ->view('emails.admin_withdraw_notification', [
                'user' => $this->user,
                'withdrawal' => $this->withdrawal
            ]);
    }
}
