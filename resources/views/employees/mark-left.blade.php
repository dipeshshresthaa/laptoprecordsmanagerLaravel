<x-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8 mt-8">
        
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center">
                <svg class="w-7 h-7 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg>
                Offboard employee
            </h2>
            <p class="mt-2 text-sm text-slate-500">
                You are about to mark <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong> ({{ $employee->emp_code }}) as having left the company. 
            </p>
        </div>

        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-8 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-amber-800 font-medium">Important security notice</p>
                    <p class="text-sm text-amber-700 mt-1">
                        Confirming this action will immediately lock this employee's record and revoke their system login access.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <form action="{{ route('employees.process-left', $employee) }}" method="POST" class="p-6 sm:p-8 space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Date of exit *</label>
                    <input type="date" name="exit_date" value="{{ old('exit_date', now()->format('Y-m-d')) }}" required max="9999-12-31" 
                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 text-sm text-slate-700">
                    @error('exit_date') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Reason for leaving *</label>
                    <input type="text" name="reason" value="{{ old('reason') }}" required placeholder="e.g. Resigned, Terminated, Contract ended" 
                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 text-sm">
                    @error('reason') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                @if($employee->userAccount && $employee->userAccount->is_active)
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 mt-6">
                    <label class="flex items-start space-x-3 cursor-pointer group">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="deactivate_user" value="1" checked 
                                   class="w-4 h-4 text-rose-600 border-slate-300 rounded focus:ring-rose-500 transition-colors">
                        </div>
                        <div>
                            <span class="text-sm font-medium text-slate-900 block group-hover:text-rose-600 transition-colors">
                                Deactivate associated user account
                            </span>
                            <span class="text-xs text-slate-500 block">
                                The username <strong>{{ $employee->userAccount->username }}</strong> will be immediately locked.
                            </span>
                        </div>
                    </label>
                </div>
                @endif

                <div class="pt-4 flex items-center justify-end space-x-4 border-t border-slate-100">
                    <a href="{{ route('employees.index') }}" class="px-5 py-2.5 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 font-medium transition-colors text-sm">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg shadow-sm font-medium transition-all text-sm">
                        Confirm offboarding
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>