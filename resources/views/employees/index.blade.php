<x-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 mt-0">

        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Employee directory</h2>
                <p class="mt-1 text-sm text-slate-500">Manage your team members and their associated laptop assignments.
                </p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row flex-wrap items-start md:items-center md:justify-between mb-8 gap-3">
            <form action="{{ route('employees.index') }}" method="GET"
                class="flex flex-row items-center gap-3 w-full md:w-auto">
                <div class="relative flex-1 md:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search employees..." class="form-input pl-10">
                </div>

                <label class="btn btn-secondary cursor-pointer shrink-0 h-[38px] !px-3">
                    <input type="checkbox" name="show_left_employees" value="1" onchange="this.form.submit()"
                        {{ $showLeftEmployees ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors">
                    <span class="ml-2 text-sm font-medium text-slate-700">Show inactive</span>
                </label>
                <button type="submit" class="hidden"></button>
            </form>

            <div class="flex flex-row flex-wrap items-center gap-3 w-full md:w-auto">
                <a href="{{ route('employees.export.pdf', ['show_left_employees' => request('show_left_employees')]) }}"
                    class="btn btn-secondary shrink-0 h-[38px]">
                    <svg class="mr-2 -ml-1 w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14v-4H8l4-4 4 4h-3v4h-2zm-3.5 2a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm9 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" />
                    </svg>
                    Export PDF
                </a>

                @if (Auth::check() && Auth::user()->is_admin)
                    <a href="{{ route('admin.employees.import') }}" class="btn btn-secondary shrink-0 h-[38px]">
                        <svg class="mr-2 -ml-1 w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Bulk upload
                    </a>
                @endif

                <a href="{{ route('employees.create') }}" class="btn btn-primary shrink-0 h-[38px]">
                    <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add employee
                </a>
            </div>
        </div>

        <div class="card">
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead class="table-head">
                        <tr>
                            <th scope="col" class="table-th">Emp code</th>
                            <th scope="col" class="table-th">Name</th>
                            <th scope="col" class="table-th">Role</th>
                            <!-- Table Header -->
                            <th scope="col" class="table-th"">
                                Docs</th>

                            <th scope="col" class="table-th !text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($employees as $emp)
                            <tr class="table-row group {{ !$emp->is_active ? 'bg-slate-50/50 opacity-75' : '' }}">
                                <td class="table-td text-sm font-medium text-slate-900">{{ $emp->emp_code }}</td>
                                <td class="table-td">
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
                                <td class="table-td">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $emp->role_badge_classes }}">
                                        {{ $emp->role_display }}
                                    </span>
                                </td>

                                <!-- Table Body Cell -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if (isset($emp->articleship_deed_path) or isset($emp->completion_certificate_path))
                                        <div class="flex items-center space-x-3">
                                            <!-- Articleship Deed -->
                                            <div class="flex flex-col items-center">
                                                <span class="text-[10px] text-slate-400 uppercase mb-1">Deed</span>
                                                @if ($emp->articleship_deed_path)
                                                    <a href="{{ route('employees.view-deed', $emp) }}" target="_blank"
                                                        title="View Articleship Deed"
                                                        class="text-emerald-600 hover:text-emerald-700 transition-colors">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <span title="No deed uploaded" class="text-slate-300">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 13h6m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2zM15 13l-3-3m0 0l-3 3m3-3v12">
                                                            </path>
                                                            <path d="M6 18L18 6" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Completion Certificate -->
                                            <div class="flex flex-col items-center border-l border-slate-200 pl-3">
                                                <span class="text-[10px] text-slate-400 uppercase mb-1">Comp</span>
                                                @if ($emp->completion_certificate_path)
                                                    <a href="{{ route('employees.view-completion', $emp) }}"
                                                        target="_blank" title="View Completion Certificate"
                                                        class="text-blue-600 hover:text-blue-700 transition-colors">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                            <path
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <span title="No completion certificate" class="text-slate-300">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 13h6m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                            </path>
                                                            <path d="M6 18L18 6" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-400 text-xs italic">N/A</span>
                                    @endif
                                </td>

                                <td class="table-td text-right text-sm font-medium">
                                    <div
                                        class="flex items-center justify-end space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">

                                        @if ($emp->is_active && $emp->role === 'ArticleTrainee')
                                            <button
                                                onclick="openUpgradeModal('{{ $emp->id }}', '{{ $emp->fullName }}')"
                                                class="action-icon icon-emerald" title="Upgrade to Staff">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7" />
                                                </svg>
                                            </button>
                                        @endif

                                        @if ($emp->is_active && Auth::user()->employee_id !== $emp->id)
                                            <a href="{{ route('employees.mark-left', $emp) }}"
                                                class="action-icon icon-amber" title="Offboard Employee">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                                                </svg>
                                            </a>
                                        @endif

                                        <a href="{{ route('employees.edit', $emp) }}" class="action-icon icon-blue"
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('employees.destroy', $emp) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete {{ $emp->first_name }}? This action cannot be undone.');"
                                            class="inline-block">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-icon icon-rose" title="Delete">
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
                                            class="btn btn-primary bg-blue-50 text-blue-600 hover:bg-blue-100 !border-transparent shadow-sm">
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

    <div id="upgradeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-1">Upgrade <span id="upgradeEmpName"></span></h3>
                <p class="text-sm text-slate-500 mb-6">Transition trainee to audit staff status.</p>

                <form id="upgradeForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">New designation</label>
                            <input type="text" name="new_designation" value="Audit Assistant" required
                                class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Completion date</label>
                            <input type="date" name="completion_date" value="{{ date('Y-m-d') }}" required
                                class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Completion certificate (PDF)</label>
                            <input type="file" name="certificate" accept=".pdf"
                                class="form-file-input !bg-blue-50 !text-blue-700 hover:!bg-blue-100">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <button type="button" onclick="closeUpgradeModal()"
                            class="px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg transition-colors">Cancel</button>
                        <button type="submit" class="btn btn-primary !bg-emerald-600 hover:!bg-emerald-700">Confirm
                            upgrade</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openUpgradeModal(id, name) {
            document.getElementById('upgradeEmpName').innerText = name;
            document.getElementById('upgradeForm').action = `/employees/${id}/upgrade`;
            document.getElementById('upgradeModal').classList.remove('hidden');
        }

        function closeUpgradeModal() {
            document.getElementById('upgradeModal').classList.add('hidden');
        }
    </script>
</x-layout>
