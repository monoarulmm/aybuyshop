<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // আপনি কোন কোন কলাম চান তা এখানে সিলেক্ট করতে পারেন
        return User::select(
            'id',
            'name',
            'email',
            'nid_number',
            'phone',
            // KYC & Payment Information
            'transaction_id',
            'payment_type',

            'address',


            // Roles, Type & Status
            'role',               // user, admin, super_admin
            'type',               // basic, premium, premium_pro
            'paid_amount',        // মোট কত টাকা পে করেছে
            'package_updated_at', // প্যাকেজ আপডেটের সময়
            'status',

            'created_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            "ID",
            "Name",
            "Email",
            "NID ID",
            "Phone",
            "transaction_id",
            "payment_type",

            "address",
            "role",               // user, admin, super_admin
            "payment type",               // basic, premium, premium_pro
            "paid_amount",        // মোট কত টাকা পে করেছে
            "package_updated_at", // প্যাকেজ আপডেটের সময়
            "status",


            "Joined Date"
        ];
    }
}
