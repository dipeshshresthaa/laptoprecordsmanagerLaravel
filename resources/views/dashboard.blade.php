<x-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 mt-4">

        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">System overview</h2>
                <p class="mt-1 text-sm text-slate-500">Welcome to the Laptop Records Manager dashboard.</p>
            </div>
            <a href="{{ route('reports.index') }}" class="btn btn-primary shrink-0">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Generate reports
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <div onclick="openDashModal('modal-available')"
                class="card p-6 flex items-center cursor-pointer hover:bg-slate-50 hover:border-emerald-300 hover:shadow-md transition-all group">
                <div class="p-4 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Available</p>
                    <p class="text-2xl font-bold text-emerald-700">{{ $availableLaptops }}</p>
                </div>
            </div>

            <div onclick="openDashModal('modal-assigned')"
                class="card p-6 flex items-center cursor-pointer hover:bg-slate-50 hover:border-blue-300 hover:shadow-md transition-all group">
                <div class="p-4 rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Assigned</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $assignedLaptops }}</p>
                </div>
            </div>

            <div onclick="openDashModal('modal-maintenance')"
                class="card p-6 flex items-center cursor-pointer hover:bg-slate-50 hover:border-rose-300 hover:shadow-md transition-all group">
                <div class="p-4 rounded-lg bg-rose-50 text-rose-600 group-hover:bg-rose-100 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">In repair</p>
                    <p class="text-2xl font-bold text-rose-700">{{ $maintenanceLaptops }}</p>
                </div>
            </div>

            <div class="card p-6 flex items-center">
                <div class="p-4 rounded-lg bg-purple-50 text-purple-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Employees</p>
                    <p class="text-2xl font-bold text-purple-700">{{ $totalEmployees }}</p>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 card">
                <div class="card-header flex justify-between items-center bg-slate-50 border-b border-slate-200">
                    <h3 class="card-title text-base">Recent assignments</h3>
                    <a href="{{ route('laptops.index') }}"
                        class="text-xs font-bold text-blue-600 hover:text-blue-800">View all inventory &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-base w-full">
                        <thead class="table-head">
                            <tr>
                                <th class="table-th">Employee</th>
                                <th class="table-th">Hardware ID</th>
                                <th class="table-th">Date</th>
                                <th class="table-th">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($recentAssignments as $assignment)
                                <tr class="table-row">
                                    <td class="table-td">
                                        <div class="font-medium text-slate-900">{{ $assignment->employee->first_name }}
                                            {{ $assignment->employee->last_name }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">
                                            {{ $assignment->employee->department ?? 'N/A' }}</div>
                                    </td>
                                    <td class="table-td font-mono text-sm text-slate-700">
                                        {{ $assignment->laptop->serial_number }}
                                    </td>
                                    <td class="table-td text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($assignment->assigned_date)->format('M d, Y') }}
                                    </td>
                                    <td class="table-td">
                                        @if ($assignment->returned_date)
                                            <span
                                                class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600">Returned</span>
                                        @else
                                            <span
                                                class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">Active</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500 italic">No
                                        recent assignment activity.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if (Auth::user()->is_admin)
                <div class="lg:col-span-1 space-y-6">
                    <div class="card p-6 bg-gradient-to-br from-slate-900 to-slate-800 text-white border-0">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-4">Administration</h3>

                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-700/50">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-500/20 rounded-lg text-blue-400 mr-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="font-medium">System users</span>
                            </div>
                            <span class="text-2xl font-bold">{{ $totalUsers }}</span>
                        </div>

                        <a href="{{ route('users.index') }}"
                            class="block w-full py-2 px-4 bg-white/10 hover:bg-white/20 rounded text-center text-sm font-bold transition-colors">
                            Manage users &rarr;
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <div id="modal-available"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-emerald-50">
                <h3 class="text-lg font-bold text-emerald-900 flex items-center"><span
                        class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Available laptops</h3>
                <button onclick="closeDashModal('modal-available')" class="text-slate-400 hover:text-slate-600"><svg
                        class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg></button>
            </div>
            <div class="overflow-y-auto p-0 flex-1">
                <table class="table-base w-full">
                    <thead class="bg-slate-50 sticky top-0">
                        <tr>
                            <th class="table-th">Hardware ID</th>
                            <th class="table-th">Model</th>
                            <th class="table-th text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($availableLaptopsList as $lap)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3">
                                    <div class="font-mono text-sm font-bold text-slate-900">{{ $lap->serial_number }}
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $lap->laptop_fa_code ?? 'No FA Code' }}
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-sm text-slate-700">{{ $lap->brand->value ?? '' }}
                                    {{ $lap->model->value ?? '' }}</td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('laptops.assign', $lap) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white rounded-lg text-xs font-bold transition-colors">
                                        Assign <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-sm text-slate-500">No laptops
                                    currently available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modal-assigned"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-blue-50">
                <h3 class="text-lg font-bold text-blue-900 flex items-center"><span
                        class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Assigned laptops</h3>
                <button onclick="closeDashModal('modal-assigned')" class="text-slate-400 hover:text-slate-600"><svg
                        class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg></button>
            </div>
            <div class="overflow-y-auto p-0 flex-1">
                <table class="table-base w-full">
                    <thead class="bg-slate-50 sticky top-0">
                        <tr>
                            <th class="table-th">Hardware ID</th>
                            <th class="table-th">Assigned To</th>
                            <th class="table-th">Assigned Since</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($assignedLaptopsList as $lap)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3">
                                    <a href="{{ route('laptops.history', $lap) }}"
                                        class="font-mono text-sm font-bold text-blue-600 hover:underline">{{ $lap->serial_number }}</a>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $lap->currentAssignment->employee->full_name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-slate-500">
                                        {{ $lap->currentAssignment->employee->department ?? '' }}</div>
                                </td>
                                <td class="px-6 py-3 text-sm text-slate-600">
                                    @if ($lap->currentAssignment)
                                        {{ $lap->currentAssignment->assigned_date->format('M d, Y') }}
                                        <span
                                            class="text-xs text-slate-400">({{ $lap->currentAssignment->assigned_date->diffForHumans() }})</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-sm text-slate-500">No laptops
                                    currently assigned.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modal-maintenance"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-rose-50">
                <h3 class="text-lg font-bold text-rose-900 flex items-center"><span
                        class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span> Laptops in repair</h3>
                <button onclick="closeDashModal('modal-maintenance')" class="text-slate-400 hover:text-slate-600"><svg
                        class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg></button>
            </div>
            <div class="overflow-y-auto p-0 flex-1">
                <table class="table-base w-full">
                    <thead class="bg-slate-50 sticky top-0">
                        <tr>
                            <th class="table-th">Hardware ID</th>
                            <th class="table-th">Vendor</th>
                            <th class="table-th">Time Elapsed</th>
                            <th class="table-th text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($maintenanceLaptopsList as $lap)
                            @php
                                $activeRepair = $lap->repairs->first();
                                $diffString = $activeRepair
                                    ? $activeRepair->sent_date->diffForHumans([
                                        'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
                                        'parts' => 2,
                                    ])
                                    : 'Unknown';
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3 font-mono text-sm font-bold text-slate-900">
                                    {{ $lap->serial_number }}</td>
                                <td class="px-6 py-3 text-sm text-slate-700">
                                    {{ $activeRepair->vendor->value ?? 'Unknown Vendor' }}</td>
                                <td class="px-6 py-3">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $diffString }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('laptops.repair_return', $lap) }}"
                                        class="text-xs font-bold text-blue-600 hover:text-blue-800">Receive &rarr;</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">No laptops
                                    currently in repair.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function openDashModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeDashModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ['modal-available', 'modal-assigned', 'modal-maintenance'].forEach(closeDashModal);
            }
        });
    </script>
</x-layout>
