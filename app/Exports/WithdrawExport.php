<?php

namespace App\Exports;

use App\Models\Withdrawal; // আপনার মডেলের নাম অনুযায়ী পরিবর্তন করবেন
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WithdrawExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * ডাটা সংগ্রহ করা
     */
    public function collection()
    {
        // উইথড্র রিকোয়েস্ট এবং ইউজারের নাম একসাথে নিয়ে আসা
        return Withdrawal::with('user')->latest()->get();
    }

    /**
     * এক্সেলের প্রতিটি রো (Row) কিভাবে সাজানো হবে
     */
    public function map($withdraw): array
    {
        return [
            $withdraw->id,
            $withdraw->user->name ?? 'N/A',
            $withdraw->amount,
            $withdraw->payment_method,
            $withdraw->account_number,
            $withdraw->status,
            $withdraw->created_at->format('d M, Y h:i A'),
        ];
    }

    /**
     * এক্সেলের কলামের শিরোনাম (Headings)
     */
    public function headings(): array
    {
        return [
            "Request ID",
            "User Name",
            "Amount",
            "Method",
            "Account Number",
            "Status",
            "Requested At"
        ];
    }
}
