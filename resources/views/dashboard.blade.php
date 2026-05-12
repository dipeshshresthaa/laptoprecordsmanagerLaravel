<x-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 mt-4">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">System overview</h2>
            <p class="mt-1 text-sm text-slate-500">Welcome to the Laptop records manager (LRM) dashboard.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="stat-card">
                <div class="stat-icon-container stat-icon-blue">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="ml-5">
                    <p class="stat-title">Total employees</p>
                    <p class="stat-value">{{ $employeeCount ?? 0 }}</p>
                </div>
            </div>

            @if(Auth::user()->is_admin)
            <div class="stat-card">
                <div class="stat-icon-container stat-icon-purple">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <div class="ml-5">
                    <p class="stat-title">System users</p>
                    <p class="stat-value">{{ $userCount ?? 0 }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-layout>