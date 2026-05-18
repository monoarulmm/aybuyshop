<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Alert</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f4f7; font-family: Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="600"
                    style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <tr>
                        <td align="center" style="background-color: #161925; padding: 40px 20px;">
                            <h1
                                style="color: #EAB308; margin: 0; font-size: 28px; letter-spacing: 2px; font-style: italic; text-transform: uppercase;">
                                TAKAID</h1>
                            <p
                                style="color: #94a3b8; margin: 10px 0 0 0; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">
                                Admin Notification System</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #1a202c; font-size: 20px; margin-bottom: 20px;">নতুন একটি অর্ডার এসেছে!
                            </h2>
                            <div
                                style="background-color: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0;">
                                <p style="margin: 5px 0;"><strong>অর্ডার আইডি:</strong> #{{ $order->id }}</p>
                                <p style="margin: 5px 0;"><strong>কাস্টমার নাম:</strong>
                                    {{ $order->user->name ?? 'Guest' }}</p>
                                <p style="margin: 5px 0;"><strong>মোট টাকা:</strong>
                                    ৳{{ number_format($order->total_amount, 2) }}</p>
                                <p style="margin: 5px 0;"><strong>ফোন:</strong> {{ $order->phone }}</p>
                            </div>
                            <div style="margin-top: 30px; text-align: center;">
                                <a href="{{ url('/admin/orders/' . $order->id) }}"
                                    style="background-color: #EAB308; color: #000; padding: 15px 25px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">অর্ডার
                                    ডিটেইলস দেখুন</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
