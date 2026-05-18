<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\UpgradeRequest;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage; // উপরে এটি ইমপোর্ট নিশ্চিত করুন
use App\Exports\WithdrawExport;

class AdminController extends Controller
{
    /**
     * ড্যাশবোর্ড স্ট্যাটাস দেখানো
     */
    public function index()
    {
        $stats = [
            'total_users'    => User::where('role', 'user')->count(),
            'pending_users'  => User::where('status', 'pending')->count(),
            'active_users'   => User::where('status', 'active')->count(),
            'total_revenue'  => User::sum('paid_amount'),
        ];
        return view('content.admin.dashboard', $stats);
    }

    /**
     * পেন্ডিং ইউজারদের তালিকা
     */
    public function pendingUsers()
    {
        $users = User::where('status', 'pending')->latest()->get();
        return view('content.admin.pending_users', compact('users'));
    }

    /**
     * ইউজার অ্যাপ্রুভ করা এবং এসএমএস পাঠানো
     */
    public function approveUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // ৬ ডিজিটের র‍্যান্ডম পাসওয়ার্ড তৈরি
        $plainPassword = rand(100000, 999999);
        $message = "অভিনন্দন! আপনার অ্যাকাউন্টটি একটিভ হয়েছে। লগইন আইডি: {$user->phone} এবং পাসওয়ার্ড: {$plainPassword}";

        // ডাটাবেজ ট্রানজেকশন শুরু (এসএমএস ফেইল করলে ডাটা সেভ হবে না)
        DB::beginTransaction();

        try {
            // ১. ডাটাবেজ আপডেট
            $user->update([
                'password'           => Hash::make($plainPassword),
                'status'             => 'active',
                'package_updated_at' => now(),
            ]);

            // ২. এসএমএস পাঠানো (SmsService ব্যবহার করে)
            $smsResponse = SmsService::sendSms($user->phone, $message);

            // ৩. এসএমএস সফল হয়েছে কি না চেক করা (BulkSMSBD অনুযায়ী ২০২ মানে সাকসেস)
            if ($smsResponse && isset($smsResponse['response_code']) && $smsResponse['response_code'] == 202) {

                DB::commit(); // এসএমএস গেছে, তাই ডাটা সেভ করো
                return back()->with('success', "ইউজার অনুমোদিত হয়েছে এবং পাসওয়ার্ড এসএমএস করা হয়েছে।");
            } else {

                DB::rollBack(); // এসএমএস যায়নি, তাই ডাটাবেজ আপডেট বাতিল করো

                // গেটওয়ে থেকে আসা মেসেজ বা ডিফল্ট এরর দেখানো
                $errorMsg = $smsResponse['msg'] ?? ($smsResponse['success'] ?? 'গেটওয়ে এরর');
                Log::error("SMS Failed for user {$user->id}: " . json_encode($smsResponse));

                return back()->with('error', "এসএমএস পাঠানো যায়নি বলে ইউজার অ্যাপ্রুভ হয়নি। কারণ: " . $errorMsg);
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Approval Error: " . $e->getMessage());
            return back()->with('error', "সিস্টেম এরর: " . $e->getMessage());
        }
    }

    /**
     * ইউজার রিজেক্ট করা
     */
    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'rejected']);
        return back()->with('error', "{$user->phone} নম্বরযুক্ত আবেদনটি রিজেক্ট করা হয়েছে।");
    }



    // সব পেন্ডিং রিকোয়েস্ট দেখা
    public function viewUpgradeRequests()
    {
        $requests = UpgradeRequest::with('user')->where('status', 'pending')->get();
        return view('content.admin.upgrade_request', compact('requests'));
    }



    // রিকোয়েস্ট অ্যাপ্রুভ করা এবং ইউজার প্রোফাইল আপডেট করা
    public function approveUpgrade($id)
    {
        $req = UpgradeRequest::findOrFail($id);
        $user = User::findOrFail($req->user_id);

        // ১. মেইন ইউজার টেবিলের ডাটা আপডেট করা
        $user->update([
            'type' => $req->target_package, // নতুন প্যাকেজ টাইপ
            'paid_amount' => $user->paid_amount + $req->amount_to_pay, // টোটাল পে করা টাকা আপডেট
            'transaction_id' => $req->transaction_id, // লেটেস্ট ট্রানজেকশন আইডি সেট করা
            'payment_type' => $req->payment_type,
            'package_updated_at' => now(), // বর্তমান সময় সেট করা
            'status' => 'active', // যদি ইউজার আগে পেন্ডিং থাকে তবে একটিভ করা
        ]);

        // ২. আপগ্রেড রিকোয়েস্টের স্ট্যাটাস 'approved' করা
        $req->update(['status' => 'approved']);

        return back()->with('success', 'User package upgraded and approved successfully!');
    }

    // রিকোয়েস্ট রিজেক্ট করা
    public function rejectUpgrade($id)
    {
        $req = UpgradeRequest::findOrFail($id);

        // শুধু স্ট্যাটাস রিজেক্ট করা
        $req->update(['status' => 'rejected']);

        return back()->with('error', 'The upgrade request has been rejected.');
    }



    //withdraw
    // ১. সব উইথড্রয়াল রিকোয়েস্ট দেখা





    public function withdraw(Request $request)
    {
        $status = $request->input('status', 'pending');
        $search = $request->input('search');

        $withdrawals = Withdrawal::with('user')
            ->where('status', $status)
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                })->orWhere('account_number', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('content.admin.withdraw_request', compact('withdrawals', 'status'));
    }

    public function exportWithdraw(Request $request)
    {
        $status = $request->input('status', 'pending');
        // আজকের তারিখ সহ ফাইল নেম জেনারেট হবে
        $fileName = "withdraw_requests_{$status}_" . now()->format('d-m-Y') . ".xlsx";
        return Excel::download(new WithdrawExport($status), $fileName);
    }

    public function approve($id)
    {
        try {
            $withdrawal = Withdrawal::findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return back()->with('error', 'This request is already ' . $withdrawal->status);
            }

            $withdrawal->update(['status' => 'approved']);

            return back()->with('success', 'Withdrawal request approved successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function reject($id)
    {
        try {
            $withdrawal = Withdrawal::findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return back()->with('error', 'This request is already ' . $withdrawal->status);
            }

            // এখানে আপনার ইউজারের টাকা ফেরত দেওয়ার লজিক থাকলে যোগ করতে পারেন
            // $withdrawal->user->increment('balance', $withdrawal->amount);

            $withdrawal->update(['status' => 'rejected']);

            return back()->with('success', 'Withdrawal request rejected!');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong!');
        }
    }




    public function userlist(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('content.admin.user_list', compact('users'));
    }




    public function exportUsers()
    {
        return Excel::download(new UsersExport, 'users_list.xlsx');
    }


    // ইনলাইন আপডেট (AJAX)
    public function updateInline(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update([
                'role'        => $request->role,
                'type'        => $request->type,
                'paid_amount' => $request->paid_amount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // ডিলিট ইউজার (AJAX)

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // ১. প্রোফাইল ইমেজ স্টোরেজ থেকে ডিলিট করা
            if ($user->profile_image) {
                // storage/app/public/ ফোল্ডারের ভেতর থেকে ফাইল ডিলিট করবে
                if (Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }
            }

            // ২. NID ইমেজ থাকলে সেটিও ডিলিট করা
            if ($user->nid_image) {
                if (Storage::disk('public')->exists($user->nid_image)) {
                    Storage::disk('public')->delete($user->nid_image);
                }
            }

            // ৩. ডাটাবেস থেকে ইউজার ডিলিট করা
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'ইউজার এবং সংশ্লিষ্ট ছবিগুলো সফলভাবে মুছে ফেলা হয়েছে!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'সার্ভার এরর: ' . $e->getMessage()
            ], 500);
        }
    }
}
