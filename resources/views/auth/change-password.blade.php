<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change password - Laptop records manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="p-8 pb-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-2xl font-bold text-center text-slate-900 tracking-tight">Update password</h2>
            <p class="mt-2 text-sm text-center text-slate-500">For security reasons, you must change your default password before proceeding.</p>
        </div>

        <div class="p-8 pt-6">
            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">New password</label>
                    <input type="password" name="password" required autofocus class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                    @error('password') <span class="text-rose-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Confirm new password</label>
                    <input type="password" name="password_confirmation" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                </div>

                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-all">
                    Update password
                </button>
            </form>
        </div>
    </div>
</body>
</html>