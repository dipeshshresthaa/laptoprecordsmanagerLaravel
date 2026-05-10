<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laptop Records Manager</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes slideInRight { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast-enter { animation: slideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .toast-exit { opacity: 0; transform: scale(0.95); transition: all 0.4s ease-in-out; }
        @keyframes shrink { from { width: 100%; } to { width: 0%; } }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-4 w-full max-w-sm pointer-events-none">
        @if (session('success'))
            <div class="toast-message toast-enter flex items-start p-4 bg-white rounded-xl shadow-lg ring-1 ring-slate-900/5 pointer-events-auto border-l-4 border-emerald-500 overflow-hidden relative">
                <div class="absolute bottom-0 left-0 h-1 bg-emerald-500 animate-[shrink_4s_linear_forwards]" style="width: 100%; animation-name: shrink; animation-duration: 4s;"></div>
                <div class="flex-shrink-0"><svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                <div class="ml-3 w-0 flex-1 pt-0.5"><p class="text-sm font-semibold text-slate-900">Success</p><p class="mt-1 text-sm text-slate-500">{{ session('success') }}</p></div>
            </div>
        @endif
    </div>

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        
        <div class="p-8 pb-6 border-b border-slate-100 bg-slate-50/50">
            <div class="flex items-center justify-center space-x-3 mb-6">
                <div class="bg-blue-600 p-2.5 rounded-xl shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-center text-slate-900 tracking-tight">Welcome back</h2>
            <p class="mt-2 text-sm text-center text-slate-500">Sign in to manage your laptop records.</p>
        </div>

        <div class="p-8 pt-6">
            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors py-2.5">
                    @error('username')
                        <span class="text-rose-500 text-xs mt-1.5 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    </div>
                    <input type="password" name="password" id="password" required
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors py-2.5">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors">
                        <span class="text-sm font-medium text-slate-600 group-hover:text-slate-900 transition-colors">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    Sign in
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toasts = document.querySelectorAll('.toast-message');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.classList.add('toast-exit');
                    setTimeout(() => toast.remove(), 400); 
                }, 4000); 
            });
        });
    </script>
</body>
</html>