<?php

namespace App\Models;

// পাসওয়ার্ড রিসেট কাজ করার জন্য এই ইন্টারফেসটি ইমপ্লিমেন্ট করা জরুরি
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',

        // KYC & Payment Information
        'transaction_id',
        'payment_type',
        'nid_number',
        'address',
        'nid_image',
        'profile_image',

        // Roles, Type & Status
        'role',               // user, admin, super_admin
        'type',               // basic, premium, premium_pro
        'paid_amount',        // মোট কত টাকা পে করেছে
        'package_updated_at', // প্যাকেজ আপডেটের সময়
        'status',             // pending, active, rejected
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'package_updated_at' => 'datetime',
            'paid_amount' => 'decimal:2', // টাকার হিসাবের জন্য ডেসিমাল কাস্ট করা ভালো
        ];
    }

    // হেল্পার মেথড (অপশনাল কিন্তু প্রফেশনাল কাজের জন্য দরকারি)
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isPremium()
    {
        return in_array($this->type, ['premium', 'premium_pro']);
    }

    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }
}
