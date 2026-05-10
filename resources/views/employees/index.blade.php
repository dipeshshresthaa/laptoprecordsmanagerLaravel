<x-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 mt-4 sm:mt-8">

        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Employee directory</h2>
                <p class="mt-1 text-sm text-slate-500">Manage your team members and their associated laptop assignments.
                </p>
            </div>

            <div
                class="mt-4 sm:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 items-start sm:items-center">

                <form action="{{ route('employees.index') }}" method="GET"
                    class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search employees..."
                            class="block w-full sm:w-64 pl-10 pr-3 py-2 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors">
                    </div>

                    <div
                        class="flex items-center bg-white border border-slate-200 rounded-lg px-3 py-2 shadow-sm shrink-0">
                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input type="checkbox" name="show_left_employees" value="1"
                                onchange="this.form.submit()" {{ $showLeftEmployees ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors">
                            <span
                                class="text-sm font-medium text-slate-700 group-hover:text-slate-900 transition-colors">Show
                                inactive</span>
                        </label>
                    </div>

                    <button type="submit" class="hidden"></button>
                </form>

                <a href="{{ route('employees.export.pdf', ['show_left_employees' => request('show_left_employees')]) }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg shadow-sm text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all shrink-0">
                    <svg class="mr-2 -ml-1 w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export PDF
                </a>

                <a href="{{ route('employees.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shrink-0">
                    <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add employee
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Emp code</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Name</th>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Role</th>
                            <th scope="col"
                                class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($employees as $emp)
                            <tr
                                class="hover:bg-slate-50 transition-colors group {{ !$emp->is_active ? 'bg-slate-50/50 opacity-75' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                    {{ $emp->emp_code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-8 w-8 rounded-full {{ $emp->is_active ? 'bg-blue-100 text-blue-700' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs mr-3">
                                            {{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}
                                        </div>
                                        <div class="text-sm font-medium text-slate-900">
                                            {{ $emp->fullName }}
                                            @if (!$emp->is_active)
                                                <span
                                                    class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-rose-100 text-rose-800">Left</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                        {{ $emp->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div
                                        class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">

                                        @if ($emp->is_active && Auth::user()->employee_id !== $emp->id)
                                            <a href="{{ route('employees.mark-left', $emp) }}"
                                                class="text-slate-400 hover:text-amber-600 transition-colors"
                                                title="Offboard Employee">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6">
                                                    </path>
                                                </svg>
                                            </a>
                                        @endif

                                        <a href="{{ route('employees.edit', $emp) }}"
                                            class="text-slate-400 hover:text-blue-600 transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>

                                        <form action="{{ route('employees.destroy', $emp) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete {{ $emp->first_name }}? This action cannot be undone.');"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-slate-400 hover:text-red-600 transition-colors"
                                                title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">No employees found</h3>
                                    <p class="mt-1 text-sm text-slate-500">Adjust your search or get started by
                                        creating a new record.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('employees.create') }}"
                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-blue-600 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            New employee
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
