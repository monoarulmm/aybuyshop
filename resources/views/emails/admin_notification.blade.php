<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Registration</title>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f4f4f7; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="600"
                    style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

                    <tr>
                        <td align="center" style="background-color: #161925; padding: 40px 20px;">
                            <h1
                                style="color: #EAB308; margin: 0; font-size: 28px; letter-spacing: 2px; font-style: italic; text-transform: uppercase;">
                                TAKAID
                            </h1>
                            <p
                                style="color: #94a3b8; margin: 10px 0 0 0; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">
                                Admin Notification System
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #1f2937; margin: 0 0 20px 0; font-size: 22px; text-align: center;">নতুন
                                রেজিস্ট্রেশন আবেদন</h2>

                            <p
                                style="color: #4b5563; font-size: 16px; line-height: 1.6; text-align: center; margin-bottom: 30px;">
                                হ্যালো অ্যাডমিন, আপনার প্ল্যাটফর্মে একজন নতুন ইউজার মেম্বারশিপের জন্য আবেদন করেছেন।
                                বিস্তারিত তথ্য নিচে দেওয়া হলো:
                            </p>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                style="background-color: #f9fafb; border-radius: 12px; padding: 20px;">
                                <tr>
                                    <td
                                        style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-size: 14px;">
                                        নাম:</td>
                                    <td
                                        style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #111827; font-size: 15px; font-weight: bold; text-align: right;">
                                        {{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-size: 14px;">
                                        ফোন নম্বর:</td>
                                    <td
                                        style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #111827; font-size: 15px; font-weight: bold; text-align: right;">
                                        {{ $user->phone }}</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #6b7280; font-size: 14px;">
                                        প্যাকেজ টাইপ:</td>
                                    <td
                                        style="padding: 10px 0; border-bottom: 1px solid #e5e7eb; color: #EAB308; font-size: 14px; font-weight: bold; text-align: right; text-transform: uppercase;">
                                        {{ $user->type }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; color: #6b7280; font-size: 14px;">ট্রানজেকশন আইডি:</td>
                                    <td
                                        style="padding: 10px 0; color: #111827; font-size: 14px; font-weight: bold; text-align: right; font-family: monospace;">
                                        {{ $user->transaction_id }}</td>
                                </tr>
                            </table>

                            <div style="text-align: center; margin-top: 40px;">
                                <a href="{{ url('/admin/pending-users') }}"
                                    style="background-color: #EAB308; color: #000000; padding: 16px 32px; text-decoration: none; border-radius: 12px; font-weight: 900; font-size: 14px; text-transform: uppercase; display: inline-block; box-shadow: 0 4px 14px rgba(234,179,8,0.3);">
                                    আবেদনটি যাচাই করুন
                                </a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td
                            style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                                এটি একটি সিস্টেম জেনারেটেড ইমেইল। দয়া করে রিপ্লাই করবেন না।
                            </p>
                            <p style="color: #9ca3af; font-size: 12px; margin: 10px 0 0 0;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
