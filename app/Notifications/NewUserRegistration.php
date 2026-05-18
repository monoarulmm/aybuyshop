<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserRegistration extends Notification
{
    use Queueable;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        // return (new \Illuminate\Notifications\Messages\MailMessage)
        // ->subject('নতুন রেজিস্ট্রেশন আবেদন: ' . $this->user->name)
        // ->greeting('হ্যালো অ্যাডমিন,')
        // ->line('আপনার সাইটে একজন নতুন ইউজার রেজিস্ট্রেশন আবেদন করেছেন।')
        // ->line('নাম: ' . $this->user->name)
        // ->line('ফোন: ' . $this->user->phone)
        // ->line('প্যাকেজ: ' . $this->user->type)
        // ->line('ট্রানজেকশন আইডি: ' . $this->user->transaction_id)
        // ->action('অ্যাডমিন প্যানেল দেখুন', url('/admin/pending-users'));
        return (new MailMessage)
            ->view('emails.admin_notification', ['user' => $this->user]);
    }
}
