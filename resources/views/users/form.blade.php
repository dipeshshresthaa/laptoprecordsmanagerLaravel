<x-layout>
    <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8 mt-4 sm:mt-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                    {{ isset($user) ? 'Edit user account' : 'Create user account' }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ isset($user) ? 'Update credentials and access levels.' : 'Provide system access to an existing employee.' }}
                </p>
            </div>
            <a href="{{ route('users.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                &larr; Back to users
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                @if(!isset($user))
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Select employee *</label>
                        <select name="employee_id" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">-- Select an eligible employee --</option>
                            @foreach($eligibleEmployees as $employee)
                                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->emp_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                @else
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Linked employee</label>
                        <input type="text" disabled value="{{ $user->employee->first_name }} {{ $user->employee->last_name }} ({{ $user->employee->emp_code }})" class="w-full rounded-lg border-slate-200 bg-slate-50 text-slate-500 shadow-sm text-sm">
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Username *</label>
                        <input type="text" name="username" value="{{ old('username', $user->username ?? '') }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('username') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            {{ isset($user) ? 'New password (Leave blank to keep current)' : 'Initial password *' }}
                        </label>
                        <input type="password" name="password" {{ isset($user) ? '' : 'required' }} class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('password') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 space-y-4">
                    <label class="flex items-center space-x-3 cursor-pointer group">
                        <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $user->is_admin ?? false) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <div>
                            <span class="text-sm font-medium text-slate-900 block group-hover:text-blue-600 transition-colors">Administrator Access</span>
                            <span class="text-xs text-slate-500 block">Grants full control over users, settings, and destructive actions.</span>
                        </div>
                    </label>

                    @if(isset($user))
                    <label class="flex items-center space-x-3 cursor-pointer group">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                        <div>
                            <span class="text-sm font-medium text-slate-900 block group-hover:text-emerald-600 transition-colors">Account Active</span>
                            <span class="text-xs text-slate-500 block">Uncheck to lock the user out without deleting their history.</span>
                        </div>
                    </label>
                    @endif
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('users.index') }}" class="px-5 py-2.5 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 font-medium transition-colors text-sm">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm font-medium transition-all text-sm">
                        {{ isset($user) ? 'Update user' : 'Create user' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>