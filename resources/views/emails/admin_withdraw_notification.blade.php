<!DOCTYPE html>
<html>

<head>
    <style>
        .email-card {
            max-width: 500px;
            margin: auto;
            border: 1px solid #eee;
            padding: 20px;
            font-family: Arial, sans-serif;
            border-radius: 10px;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            padding: 20px;
            line-height: 1.6;
        }

        .details-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .details-table td {
            padding: 8px;
            border-bottom: 1px solid #f4f4f4;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="email-card">
        <div class="header">
            <h2>New Withdrawal Request</h2>
        </div>
        <div class="content">
            <p>হ্যালো অ্যাডমিন,</p>
            <p>আপনার সাইটে একজন ইউজার নতুন উইথড্র রিকোয়েস্ট পাঠিয়েছেন। বিস্তারিত নিচে দেওয়া হলো:</p>

            <table class="details-table">
                <tr>
                    <td class="label">নাম:</td>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <td class="label">ইমেইল:</td>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <td class="label">টাকার পরিমাণ:</td>
                    <td style="color: green; font-weight: bold;">৳{{ number_format($withdrawal->amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">পেমেন্ট মেথড:</td>
                    <td>{{ strtoupper($withdrawal->payment_method) }}</td>
                </tr>
                <tr>
                    <td class="label">অ্যাকাউন্ট নম্বর:</td>
                    <td>{{ $withdrawal->account_number }}</td>
                </tr>
                <tr>
                    <td class="label">সময়:</td>
                    <td>{{ $withdrawal->created_at->format('d M Y, h:i A') }}</td>
                </tr>
            </table>

            <div style="text-align: center;">
                <a href="{{ url('/admin/withdraw') }}" class="btn">অ্যাডমিন প্যানেলে দেখুন</a>
            </div>
        </div>
        <p style="font-size: 11px; color: #888; text-align: center; margin-top: 20px;">
            এটি একটি অটো-জেনারেটেড ইমেইল। দয়া করে এখানে রিপ্লাই দিবেন না।
        </p>
    </div>
</body>

</html>
