<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laptop Records Manager</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes slideInRight { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast-enter { animation: slideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .toast-exit { opacity: 0; transform: translateX(20px); transition: all 0.4s ease-in-out; }
        @keyframes shrink { from { width: 100%; } to { width: 0%; } }
    </style>
</head>

<body class="bg-slate-50 text-slate-600 font-sans antialiased selection:bg-blue-100 selection:text-blue-900 relative">

    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col-reverse gap-4 w-full max-w-sm pointer-events-none">
        @if (session('success'))
            <div class="toast-message toast-enter flex items-start p-4 bg-white rounded-xl shadow-xl border border-slate-200 border-l-4 border-l-emerald-500 overflow-hidden relative pointer-events-auto">
                <div class="absolute top-0 left-0 h-1 bg-emerald-500 animate-[shrink_4s_linear_forwards]" style="width: 100%;"></div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-bold text-slate-900">Success</p>
                    <p class="mt-1 text-sm text-slate-500">{{ session('success') }}</p>
                </div>
                <button type="button" class="toast-close-btn close-toast ml-4 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif
        @if ($errors->any())
            <div class="toast-message toast-enter flex items-start p-4 bg-white rounded-xl shadow-xl border border-slate-200 border-l-4 border-l-rose-500 overflow-hidden relative pointer-events-auto">
                <div class="absolute top-0 left-0 h-1 bg-rose-500 animate-[shrink_6s_linear_forwards]" style="width: 100%; animation-duration: 6s;"></div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-bold text-slate-900">Action Required</p>
                    <p class="mt-1 text-sm text-slate-500">{{ $errors->first() }}</p>
                </div>
                <button type="button" class="toast-close-btn close-toast ml-4 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif
    </div>

    <div x-data="{ 
            isCollapsed: localStorage.getItem('sidebarCollapsed') !== null ? localStorage.getItem('sidebarCollapsed') === 'true' : true,
            toggleSidebar() {
                this.isCollapsed = !this.isCollapsed;
                localStorage.setItem('sidebarCollapsed', this.isCollapsed);
            }
         }" 
         class="h-screen flex overflow-hidden bg-slate-50 text-slate-600">
        
        <aside :class="isCollapsed ? 'w-20' : 'w-64'" class="bg-white border-r border-slate-200 flex flex-col shrink-0 relative transition-all duration-300 ease-in-out z-30">
            <div class="p-6 flex items-center gap-3 overflow-hidden border-b border-slate-100">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-md shadow-blue-600/20 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="12" rx="2"></rect>
                        <path d="M2 20h20"></path>
                        <line x1="7" y1="8" x2="17" y2="8"></line>
                        <line x1="7" y1="12" x2="13" y2="12"></line>
                    </svg>
                </div>
                <span x-show="!isCollapsed" style="display: none;" x-transition.opacity.duration.300ms class="font-bold font-display text-sm text-slate-900 tracking-tight leading-tight whitespace-nowrap">
                    Laptop Records<br><span class="text-blue-600 text-[10px] uppercase tracking-widest font-black">Manager</span>
                </span>
            </div>

            <nav class="flex-1 px-4 space-y-1 mt-4">
                <a href="{{ route('dashboard') }}" :class="isCollapsed ? 'justify-center px-0' : ''" class="sidebar-link group {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}" :title="isCollapsed ? 'Dashboard' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" :class="isCollapsed ? '' : 'mr-3'"><rect width="7" height="9" x="3" y="3" rx="1"></rect><rect width="7" height="5" x="14" y="3" rx="1"></rect><rect width="7" height="9" x="14" y="12" rx="1"></rect><rect width="7" height="5" x="3" y="16" rx="1"></rect></svg>
                    <span x-show="!isCollapsed" style="display: none;" x-transition.opacity.duration.300ms class="whitespace-nowrap">Dashboard</span>
                </a>
                
                <a href="{{ route('employees.index') }}" :class="isCollapsed ? 'justify-center px-0' : ''" class="sidebar-link group {{ request()->routeIs('employees.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}" :title="isCollapsed ? 'Employees' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors {{ request()->routeIs('employees.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" :class="isCollapsed ? '' : 'mr-3'"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span x-show="!isCollapsed" style="display: none;" x-transition.opacity.duration.300ms class="whitespace-nowrap">Employees</span>
                </a>

                <a href="{{ route('laptops.index') }}" :class="isCollapsed ? 'justify-center px-0' : ''" class="sidebar-link group {{ request()->routeIs('laptops.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}" :title="isCollapsed ? 'Hardware' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors {{ request()->routeIs('laptops.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" :class="isCollapsed ? '' : 'mr-3'"><path d="M20 16V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v9m16 0H4m16 0 1.28 2.55a1 1 0 0 1-.9 1.45H3.62a1 1 0 0 1-.9-1.45L4 16"/></svg>
                    <span x-show="!isCollapsed" style="display: none;" x-transition.opacity.duration.300ms class="whitespace-nowrap">Hardware</span>
                </a>

                <a href="{{ route('reports.index') }}" :class="isCollapsed ? 'justify-center px-0' : ''" class="sidebar-link group {{ request()->routeIs('reports.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}" :title="isCollapsed ? 'Reports' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors {{ request()->routeIs('reports.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" :class="isCollapsed ? '' : 'mr-3'"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                    <span x-show="!isCollapsed" style="display: none;" x-transition.opacity.duration.300ms class="whitespace-nowrap">Reports</span>
                </a>

                <a href="{{ route('lookups.index') }}" :class="isCollapsed ? 'justify-center px-0' : ''" class="sidebar-link group {{ request()->routeIs('lookups.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}" :title="isCollapsed ? 'Settings' : ''">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors {{ request()->routeIs('lookups.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" :class="isCollapsed ? '' : 'mr-3'"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                    <span x-show="!isCollapsed" style="display: none;" x-transition.opacity.duration.300ms class="whitespace-nowrap">Settings</span>
                </a>
                
                @if (Auth::user()->is_admin)
                    <a href="{{ route('users.index') }}" :class="isCollapsed ? 'justify-center px-0' : ''" class="sidebar-link group {{ request()->routeIs('users.*') ? 'sidebar-link-active' : 'sidebar-link-inactive' }}" :title="isCollapsed ? 'User Access' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 transition-colors {{ request()->routeIs('users.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" :class="isCollapsed ? '' : 'mr-3'"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span x-show="!isCollapsed" style="display: none;" x-transition.opacity.duration.300ms class="whitespace-nowrap">User Access</span>
                    </a>
                @endif
            </nav>

            <button @click="toggleSidebar()" class="absolute -right-3 top-20 w-6 h-6 bg-white border border-slate-300 rounded-full flex items-center justify-center text-slate-400 hover:text-blue-600 transition-all z-50 shadow-sm">
                <svg x-show="isCollapsed" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                <svg x-show="!isCollapsed" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden relative">
            
            <header class="h-16 bg-white/90 backdrop-blur-md border-b border-slate-200 px-8 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center bg-slate-100 px-4 py-1.5 border border-transparent rounded-full focus-within:bg-white focus-within:border-blue-300 focus-within:ring-2 focus-within:ring-blue-50 transition-all group">
                    {{-- Search bar placeholder --}}
                </div>

                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest leading-none mb-1">Authenticated</p>
                            <p class="text-sm text-slate-900 font-bold">{{ Auth::user()->employee->full_name ?? Auth::user()->username }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold border-2 border-white shadow-md">
                            {{ substr(Auth::user()->employee->first_name ?? Auth::user()->username, 0, 1) }}
                        </div>
                    </div>
                    
                    <div class="w-px h-6 bg-slate-200"></div>
                    
                    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all border border-transparent" title="Logout">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-slate-50 p-5 custom-scrollbar relative z-10">
                {{ $slot }}
            </main>

            <footer class="h-10 bg-white border-t border-slate-200 px-8 flex items-center justify-between text-[9px] text-slate-500 font-bold tracking-[0.1em] shrink-0 z-20 relative">
                <div class="flex items-center">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 shadow-sm border border-emerald-600 animate-pulse"></span>
                    DATABASE CONNECTED: REGISTRY SYNCED
                </div>
                <div class="flex items-center space-x-4 text-slate-400">
                    <span>V{{ date('Y') }}.4.0-STABLE</span>
                    <span class="w-px h-2 bg-slate-300"></span>
                    <span>LOGGED AS {{ Auth::user()->is_admin ? 'ADMINISTRATOR' : 'USER' }}</span>
                </div>
            </footer>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toasts = document.querySelectorAll('.toast-message');
            toasts.forEach(toast => {
                const isError = toast.querySelector('.bg-rose-500') !== null;
                const duration = isError ? 6000 : 4000;
                const removeToast = () => {
                    toast.classList.add('toast-exit');
                    setTimeout(() => toast.remove(), 400);
                };
                const timer = setTimeout(removeToast, duration);
                const closeBtn = toast.querySelector('.close-toast');
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => {
                        clearTimeout(timer);
                        removeToast();
                    });
                }
            });
        });
    </script>
</body>
</html>