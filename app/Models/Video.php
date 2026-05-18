<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    /**
     * যে ফিল্ডগুলোতে ডাটা ইনসার্ট করা যাবে।
     */
    protected $fillable = [
        'title',
        'video_type',
        'video_url',
        'local_video',
        'earning_per_view',
        'duration',
        'status',
    ];

    /**
     * ডাটা টাইপ কাস্টিং (সঠিক ফরমেটে ডাটা পাওয়ার জন্য)।
     */
    protected $casts = [
        'earning_per_view' => 'decimal:2',
        'duration' => 'integer',
        'status' => 'boolean',
    ];

    /**
     * যদি ভিডিওর কোনো থাম্বনেইল বা স্পেশাল লজিক থাকে তবে এখানে ফাংশন লিখতে পারেন।
     * উদাহরণস্বরূপ: ভিডিওটি কি লোকাল নাকি অনলাইন তা চেক করা।
     */
    public function isLocal()
    {
        return $this->video_type === 'local';
    }
}
