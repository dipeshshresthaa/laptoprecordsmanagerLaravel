<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laptop Records Manager</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(120%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-enter {
            animation: slideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .toast-exit {
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.4s ease-in-out;
        }

        @keyframes shrink {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 font-sans antialiased selection:bg-blue-100 selection:text-blue-900 relative">

    <div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-4 w-full max-w-sm pointer-events-none">

        @if (session('success'))
            <div
                class="toast-message toast-enter flex items-start p-4 bg-white rounded-xl shadow-lg ring-1 ring-slate-900/5 pointer-events-auto border-l-4 border-emerald-500 overflow-hidden relative">
                <div class="absolute bottom-0 left-0 h-1 bg-emerald-500 animate-[shrink_4s_linear_forwards]"
                    style="width: 100%; animation-name: shrink;"></div>

                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-slate-900">Success</p>
                    <p class="mt-1 text-sm text-slate-500">{{ session('success') }}</p>
                </div>
                <button type="button"
                    class="ml-4 flex-shrink-0 text-slate-400 hover:text-slate-500 focus:outline-none close-toast">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="toast-message toast-enter flex items-start p-4 bg-white rounded-xl shadow-lg ring-1 ring-slate-900/5 pointer-events-auto border-l-4 border-rose-500 overflow-hidden relative">
                <div class="absolute bottom-0 left-0 h-1 bg-rose-500 animate-[shrink_6s_linear_forwards]"
                    style="width: 100%; animation-name: shrink; animation-duration: 6s;"></div>

                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-slate-900">Action Required</p>
                    <p class="mt-1 text-sm text-slate-500">{{ $errors->first() }}</p>
                </div>
                <button type="button"
                    class="ml-4 flex-shrink-0 text-slate-400 hover:text-slate-500 focus:outline-none close-toast">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="toast-message toast-enter flex items-start p-4 bg-white rounded-xl shadow-lg ring-1 ring-slate-900/5 pointer-events-auto border-l-4 border-rose-500 overflow-hidden relative">
                <div class="absolute bottom-0 left-0 h-1 bg-rose-500 animate-[shrink_6s_linear_forwards]"
                    style="width: 100%; animation-name: shrink; animation-duration: 6s;"></div>

                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-semibold text-slate-900">Error</p>
                    <p class="mt-1 text-sm text-slate-500">{{ session('error') }}</p>
                </div>
                <button type="button"
                    class="ml-4 flex-shrink-0 text-slate-400 hover:text-slate-500 focus:outline-none close-toast">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <div class="min-h-screen flex flex-col">
        <nav class="bg-white border-b border-slate-200 sticky top-0 z-10 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 w-full">

                    <div class="flex items-center">
                        <div class="flex items-center space-x-3 mr-8 shrink-0">
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center space-x-3 overflow-hidden focus:outline-none hover:opacity-80 transition-opacity cursor-pointer">
                                <svg class="w-8 h-8 text-blue-500 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="font-bold text-xl tracking-tight text-slate-900">Laptop Records
                                    Manager</span>
                            </a>
                        </div>

                        <div class="hidden sm:flex sm:space-x-8">
                            <a href="{{ route('employees.index') }}"
                                class="{{ request()->routeIs('employees.*') ? 'border-blue-500 text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                Employees
                            </a>

                            <a href="{{ route('laptops.index') }}"
                                class="{{ request()->routeIs('laptops.*') ? 'border-blue-500 text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                Laptops
                            </a>

                            <a href="{{ route('lookups.index') }}"
                                class="{{ request()->routeIs('lookups.*') ? 'border-blue-500 text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                Settings
                            </a>

                            <a href="{{ route('reports.index') }}"
                                class="{{ request()->routeIs('reports.*') ? 'border-blue-500 text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                Firm reports
                            </a>

                            @if (Auth::user()->is_admin)
                                <a href="{{ route('users.index') }}"
                                    class="{{ request()->routeIs('users.*') ? 'border-blue-500 text-slate-900' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                    Users
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center space-x-6">
                        <div class="hidden sm:flex flex-col text-right">
                            <span class="text-xs text-slate-500 uppercase font-semibold tracking-wider">Logged in
                                as</span>
                            <span class="text-sm font-bold text-slate-900">
                                {{ Auth::user()->employee->full_name ?? Auth::user()->username }}
                            </span>
                        </div>

                        <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 hover:text-rose-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-colors group">
                                Logout
                                <svg class="w-4 h-4 ml-2 text-slate-400 group-hover:text-rose-600 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </nav>

        <main class="flex-grow">
            {{ $slot }}
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toasts = document.querySelectorAll('.toast-message');

            toasts.forEach(toast => {
                // Determine duration: 4s for success, 6s for errors
                const isError = toast.classList.contains('border-rose-500');
                const duration = isError ? 6000 : 4000;

                // Function to trigger fade out
                const removeToast = () => {
                    toast.classList.add('toast-exit');
                    setTimeout(() => toast.remove(), 400); // Wait for CSS transition
                };

                // Auto-remove after duration
                const timer = setTimeout(removeToast, duration);

                // Allow manual close via the X button
                const closeBtn = toast.querySelector('.close-toast');
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => {
                        clearTimeout(timer); // Stop the auto-timer
                        removeToast();
                    });
                }
            });
        });
    </script>
</body>

</html>
