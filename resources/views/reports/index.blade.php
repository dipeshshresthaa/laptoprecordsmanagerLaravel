<x-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 mt-4">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Firm reports</h2>
            <p class="mt-1 text-sm text-slate-500">View partner directories, trainee registers, and principal allocations.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

            <form action="{{ route('reports.index') }}" method="GET"
                class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search name or department..."
                        class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm shadow-sm transition-colors">
                </div>

                <div class="flex items-center shrink-0">
                    <select name="status" onchange="this.form.submit()"
                        class="block w-full py-2 pl-3 pr-10 text-base border-slate-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg bg-white shadow-sm font-medium transition-colors">
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>🟢 Active employees</option>
                        <option value="left" {{ $status === 'left' ? 'selected' : '' }}>🔴 Left / Inactive employees</option>
                    </select>
                </div>
                <button type="submit" class="hidden"></button>
            </form>

            <div class="border-b border-slate-200 bg-white px-4 pt-4 flex flex-col md:flex-row md:justify-between md:items-end gap-4">
                <nav class="-mb-px flex space-x-6 overflow-x-auto pb-1" aria-label="Tabs">
                    <button onclick="switchTab(0)" id="tab-btn-0" class="tab-button whitespace-nowrap py-3 px-1 border-b-2 font-bold text-sm border-blue-600 text-blue-600">
                        👨‍💼 Partners list ({{ $partners->count() }})
                    </button>
                    <button onclick="switchTab(1)" id="tab-btn-1" class="tab-button whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300">
                        📝 Trainee register ({{ $trainees->count() }})
                    </button>
                    <button onclick="switchTab(2)" id="tab-btn-2" class="tab-button whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300">
                        📊 Principal summary ({{ $principalStats->count() }})
                    </button>
                    <button onclick="switchTab(3)" id="tab-btn-3" class="tab-button whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300">
                        📋 Master roster ({{ $totalStaff->count() }})
                    </button>
                </nav>

                <div class="pb-3 shrink-0">
                    <a href="{{ route('reports.export_comprehensive', ['search' => $search]) }}"
                        class="inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-xs font-bold rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                        <svg class="mr-1.5 h-4 w-4 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14v-4H8l4-4 4 4h-3v4h-2zm-3.5 2a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm9 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
                        Export comprehensive PDF
                    </a>
                </div>
            </div>

            <div id="tab-content-0" class="tab-content block p-6">
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-3">Name</th>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-3">Department</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($partners as $partner)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 text-sm font-medium text-slate-900">{{ $partner->first_name }} {{ $partner->last_name }}</td>
                                    <td class="py-3 text-sm text-slate-600">{{ $partner->department }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="py-4 text-center text-sm text-slate-500">No partners found matching criteria.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="tab-content-1" class="tab-content hidden p-6">
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-3">Trainee name</th>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-3">Assigned principal</th>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-3">Department</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($trainees as $trainee)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 text-sm font-medium text-slate-900">{{ $trainee->first_name }} {{ $trainee->last_name }}</td>
                                    <td class="py-3 text-sm text-slate-600">{{ $trainee->principal->first_name ?? 'Unassigned' }} {{ $trainee->principal->last_name ?? '' }}</td>
                                    <td class="py-3 text-sm text-slate-600">{{ $trainee->department }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-4 text-center text-sm text-slate-500">No trainees found matching criteria.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="tab-content-2" class="tab-content hidden p-6">
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-3">Principal name</th>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-3">Assigned trainees</th>
                                <th class="text-right text-xs font-semibold text-slate-500 uppercase pb-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($principalStats as $stat)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 text-sm font-medium text-slate-900">{{ $stat->principal->first_name ?? 'Unknown' }} {{ $stat->principal->last_name ?? '' }}</td>
                                    <td class="py-3 text-sm text-slate-600">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ $stat->trainee_count }}</span>
                                    </td>
                                    <td class="py-3 text-right text-sm">
                                        <button onclick="openDetailsModal('{{ route('reports.api.trainees', ['id' => $stat->principal_id, 'status' => $status]) }}', '{{ addslashes(($stat->principal->first_name ?? '') . ' ' . ($stat->principal->last_name ?? '')) }}')"
                                            class="text-blue-600 hover:text-blue-900 font-medium">
                                            View details &rarr;
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-4 text-center text-sm text-slate-500">No principals found matching criteria.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="tab-content-3" class="tab-content hidden p-6">
                <div class="overflow-x-auto w-full">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-3">Employee name</th>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-3">Role</th>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-3">Department</th>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($totalStaff as $staff)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3 text-sm font-bold text-slate-900">
                                        {{ $staff->first_name }} {{ $staff->last_name }}
                                        <div class="text-xs text-slate-500 font-normal mt-0.5">Assigned to: {{ $staff->principal ? $staff->principal->first_name . ' ' . $staff->principal->last_name : 'None' }}</div>
                                    </td>
                                    <td class="py-3 text-sm font-medium text-slate-600">{{ $staff->role }}</td>
                                    <td class="py-3 text-sm text-slate-600">{{ $staff->department ?? '-' }}</td>
                                    <td class="py-3 text-sm">
                                        @if ($staff->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Left
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-center text-sm text-slate-500">No employees found matching criteria.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="detailsModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Trainees under principal</h3>
                        <button onclick="closeDetailsModal()" class="text-slate-400 hover:text-slate-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
                        <div id="modal-loading" class="text-center py-8 text-slate-500">Loading data...</div>
                        <table id="modal-table" class="min-w-full divide-y divide-slate-200 hidden">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-2">Name</th>
                                    <th class="text-left text-xs font-semibold text-slate-500 uppercase pb-2">Department</th>
                                </tr>
                            </thead>
                            <tbody id="modal-tbody" class="divide-y divide-slate-100"></tbody>
                        </table>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-end">
                        <button type="button" onclick="closeDetailsModal()" class="inline-flex justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(index) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('border-blue-600', 'text-blue-600', 'font-bold');
                btn.classList.add('border-transparent', 'text-slate-500', 'font-medium');
            });
            document.getElementById(`tab-content-${index}`).classList.remove('hidden');
            const activeBtn = document.getElementById(`tab-btn-${index}`);
            activeBtn.classList.remove('border-transparent', 'text-slate-500', 'font-medium');
            activeBtn.classList.add('border-blue-600', 'text-blue-600', 'font-bold');
        }

        function openDetailsModal(url, principalName) {
            document.getElementById('detailsModal').classList.remove('hidden');
            document.getElementById('modal-title').innerText = `Trainees under ${principalName}`;
            const loading = document.getElementById('modal-loading');
            const table = document.getElementById('modal-table');
            const tbody = document.getElementById('modal-tbody');

            loading.classList.remove('hidden');
            table.classList.add('hidden');
            tbody.innerHTML = '';

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="2" class="py-4 text-sm text-slate-500 text-center">No trainees found.</td></tr>';
                    } else {
                        data.forEach(trainee => {
                            tbody.innerHTML += `
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 text-sm font-medium text-slate-900">${trainee.first_name} ${trainee.last_name}</td>
                                    <td class="py-3 text-sm text-slate-600">${trainee.department || '-'}</td>
                                </tr>`;
                        });
                    }
                    loading.classList.add('hidden');
                    table.classList.remove('hidden');
                })
                .catch(error => {
                    loading.innerHTML = '<span class="text-rose-500">Error loading data.</span>';
                    console.error('Error:', error);
                });
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }
    </script>
</x-layout>