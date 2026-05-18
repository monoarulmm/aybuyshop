<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin/Staff Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f111a;
            color: white;
        }

        .input-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 0.8rem;
            width: 100%;
            color: white;
            outline: none;
            transition: 0.3s;
        }

        .input-box:focus {
            border-color: #eab308;
            background: rgba(255, 255, 255, 0.08);
        }

        option {
            background: #161925;
            color: white;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">

    @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500 text-red-500 p-4 rounded-xl mb-4 text-xs">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="max-w-md mx-auto py-10 px-4">
        <div class="bg-[#161925] border border-white/10 p-8 rounded-[2.5rem]">
            <h2 class="text-xl font-bold text-white mb-6 uppercase tracking-widest italic">Create New <span
                    class="text-yellow-500">Admin/Staff</span></h2>

            <form action="{{ route('admin.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[10px] text-gray-400 font-black uppercase">Select Role</label>
                    <select name="role"
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-3 text-white mt-1">
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                        <option value="owner">Owner</option>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] text-gray-400 font-black uppercase">Phone Number</label>
                    <input type="text" name="phone" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-3 text-white mt-1"
                        placeholder="017XXXXXXXX">
                </div>

                <div>
                    <label class="text-[10px] text-gray-400 font-black uppercase">Email Address</label>
                    <input type="email" name="email" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-3 text-white mt-1"
                        placeholder="admin@example.com">
                </div>

                <div>
                    <label class="text-[10px] text-gray-400 font-black uppercase">Password</label>
                    <input type="password" name="password" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-3 text-white mt-1">
                </div>

                <div>
                    <label class="text-[10px] text-gray-400 font-black uppercase">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl p-3 text-white mt-1">
                </div>

                <button type="submit"
                    class="w-full bg-yellow-500 text-black font-black py-4 rounded-xl shadow-lg hover:scale-105 transition uppercase text-xs mt-4">
                    Create Account
                </button>
            </form>
        </div>
    </div>
</body>

</html>
