<x-layout>
    <div class="max-w-7xl mx-auto space-y-8 mt-4">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="animate-[fade-in-right_0.5s_ease-out]">
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                    <span class="text-slate-500 font-bold text-[10px] uppercase tracking-[0.2em]">Live Laptop and Employee Status</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight font-display">Laptop and manpower overview</h2>
            </div>
            
            <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center px-6 py-3 btn-primary bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-2xl group text-xs uppercase tracking-widest font-black transition-all shadow-md animate-[fade-in-left_0.5s_ease-out]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Export Datasets
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div onclick="openDashModal('modal-available')" class="bg-white border border-slate-200 rounded-[2rem] p-6 flex items-center justify-between shadow-sm cursor-pointer hover:border-emerald-300 hover:-translate-y-1 transition-all group">
                <div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Available</p>
                    <p class="text-3xl font-black text-slate-900">{{ $availableLaptops }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
            </div>

            <div onclick="openDashModal('modal-assigned')" class="bg-white border border-slate-200 rounded-[2rem] p-6 flex items-center justify-between shadow-sm cursor-pointer hover:border-indigo-300 hover:-translate-y-1 transition-all group">
                <div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Assigned</p>
                    <p class="text-3xl font-black text-slate-900">{{ $assignedLaptops }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><polyline points="16 11 18 13 22 9"></polyline></svg>
                </div>
            </div>

            <div onclick="openDashModal('modal-maintenance')" class="bg-white border border-slate-200 rounded-[2rem] p-6 flex items-center justify-between shadow-sm cursor-pointer hover:border-rose-300 hover:-translate-y-1 transition-all group">
                <div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">In Repair</p>
                    <p class="text-3xl font-black text-slate-900">{{ $maintenanceLaptops }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center group-hover:bg-rose-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-[2rem] p-6 flex items-center justify-between shadow-sm">
                <div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Employees</p>
                    <p class="text-3xl font-black text-slate-900">{{ $totalEmployees }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 text-slate-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden flex flex-col shadow-sm">
                    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-600 flex items-center">
                            <svg class="w-4 h-4 mr-3 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            Recent Assignments
                        </h3>
                        <span class="text-[9px] bg-indigo-50 text-indigo-600 border border-indigo-200 px-2 py-0.5 rounded-lg font-bold uppercase tracking-widest">Real-time Feed</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] text-slate-500 border-b border-slate-100 uppercase tracking-widest bg-white">
                                    <th class="px-6 py-4 font-black">Employee</th>
                                    <th class="px-6 py-4 font-black">Hardware ID</th>
                                    <th class="px-6 py-4 font-black text-center">Status</th>
                                    <th class="px-6 py-4 font-black text-right">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($recentAssignments as $assignment)
                                    <tr class="group hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-700 text-[10px] font-black mr-4 border border-slate-200">
                                                    {{ substr($assignment->employee->first_name, 0, 1) }}{{ substr($assignment->employee->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-900 text-sm">
                                                        {{ $assignment->employee->first_name }} {{ $assignment->employee->last_name }}
                                                    </div>
                                                    <div class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mt-0.5">
                                                        {{ $assignment->employee->department ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-mono text-xs text-indigo-600 font-bold bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100 tracking-widest">
                                                {{ $assignment->laptop->serial_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($assignment->returned_date)
                                                <span class="px-2 py-1 text-[9px] font-black uppercase tracking-[0.2em] rounded-lg border bg-slate-50 text-slate-500 border-slate-200">
                                                    Returned
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-[9px] font-black uppercase tracking-[0.2em] rounded-lg border bg-emerald-50 text-emerald-600 border-emerald-200">
                                                    Active
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right text-xs text-slate-500 font-bold font-mono">
                                            {{ \Carbon\Carbon::parse($assignment->assigned_date)->format('M d') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500 italic">No recent assignment activity.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-blue-600 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-md border border-indigo-500 group">
                    <div class="absolute -top-4 -right-4 p-8 text-white/10 group-hover:scale-110 transition-transform duration-700">
                        <svg class="w-32 h-32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                    </div>
                    <h4 class="text-xs font-black uppercase tracking-[0.3em] opacity-80 mb-6 flex items-center relative z-10">
                        Quick Action
                    </h4>
                    <p class="text-xl font-light leading-tight mb-8 relative z-10">
                        Start assigning new laptops today.
                    </p>
                    <a href="{{ route('laptops.index') }}" class="block text-center w-full py-4 bg-white text-blue-600 font-black rounded-2xl shadow-lg hover:bg-slate-50 transition-all transform active:scale-[0.98] text-xs uppercase tracking-widest relative z-10">
                        Assign laptops
                    </a>
                </div>

                <div class="bg-white border border-slate-200 rounded-[2rem] p-8 shadow-sm">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-8">Hardware Allocation</h4>
                    <div class="space-y-6">
                        @forelse($allocations as $item)
                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-xs text-slate-700 font-bold uppercase tracking-widest">{{ $item['name'] }}</span>
                                    <span class="text-[10px] font-mono font-bold text-slate-500">{{ $item['val'] }}%</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200/50">
                                    <div class="h-full {{ $item['color'] }} rounded-full transition-all duration-1000 ease-out" style="width: {{ $item['val'] }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400 text-center italic">No hardware records to display.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-available" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden border border-slate-200">
            <div class="px-8 py-6 border-b border-emerald-100 flex justify-between items-center bg-emerald-50/50">
                <h3 class="text-lg font-black text-emerald-900 uppercase tracking-widest text-sm flex items-center">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-3 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span> Available Laptops Pool
                </h3>
                <button onclick="closeDashModal('modal-available')" class="text-emerald-400 hover:text-emerald-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="overflow-y-auto p-0 flex-1">
                <table class="w-full">
                    <thead class="bg-slate-50 sticky top-0 border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Serial Number</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Brand / Model</th>
                            <th class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($availableLaptopsList as $lap)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="font-mono text-sm font-black text-slate-900 leading-tight tracking-widest">{{ $lap->serial_number }}</div>
                                    <div class="text-[9px] text-slate-500 font-bold uppercase mt-1 tracking-widest">{{ $lap->laptop_fa_code ?? 'NO FA CODE' }}</div>
                                </td>
                                <td class="px-8 py-5 text-xs text-slate-600 font-bold uppercase tracking-widest">
                                    {{ $lap->brand->value ?? '' }} / {{ $lap->model->value ?? '' }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('laptops.assign', $lap) }}" class="inline-block px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest border border-indigo-100 transition-all">
                                        Assign Device
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-8 text-center text-sm font-medium text-slate-400">No laptops available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modal-assigned" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden border border-slate-200">
            <div class="px-8 py-6 border-b border-indigo-100 flex justify-between items-center bg-indigo-50/50">
                <h3 class="text-lg font-black text-indigo-900 uppercase tracking-widest text-sm flex items-center">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full mr-3 shadow-[0_0_8px_rgba(99,102,241,0.5)]"></span> Active Hardware Assignments
                </h3>
                <button onclick="closeDashModal('modal-assigned')" class="text-indigo-400 hover:text-indigo-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="overflow-y-auto p-0 flex-1">
                <table class="w-full">
                    <thead class="bg-slate-50 sticky top-0 border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Hardware ID</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Assigned Personnel</th>
                            <th class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Assigned On</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($assignedLaptopsList as $lap)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-5">
                                    <a href="{{ route('laptops.history', $lap) }}" class="font-mono text-sm font-black text-indigo-600 hover:text-indigo-500 transition-colors cursor-pointer tracking-widest underline decoration-indigo-200 underline-offset-4">
                                        {{ $lap->serial_number }}
                                    </a>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-black text-slate-900 uppercase tracking-widest">{{ $lap->currentAssignment->employee->full_name ?? 'Unknown' }}</div>
                                    <div class="text-[10px] text-slate-500 font-bold uppercase mt-1 tracking-[0.2em]">{{ $lap->currentAssignment->employee->department ?? '' }}</div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    @if ($lap->currentAssignment)
                                        <div class="text-xs font-bold text-slate-500 font-mono">
                                            {{ $lap->currentAssignment->assigned_date->format('M d, Y') }}
                                        </div>
                                        <div class="text-[9px] text-emerald-500 font-black uppercase mt-1 tracking-widest">Active Assignment</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-8 text-center text-sm font-medium text-slate-400">No active assignments.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modal-maintenance" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden border border-slate-200">
            <div class="px-8 py-6 border-b border-rose-100 flex justify-between items-center bg-rose-50/50">
                <h3 class="text-lg font-black text-rose-900 uppercase tracking-widest text-sm flex items-center">
                    <span class="w-2 h-2 bg-rose-500 rounded-full mr-3 shadow-[0_0_8px_rgba(244,63,94,0.5)]"></span> Hardware Repair Queue
                </h3>
                <button onclick="closeDashModal('modal-maintenance')" class="text-rose-400 hover:text-rose-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="overflow-y-auto p-0 flex-1">
                <table class="w-full">
                    <thead class="bg-slate-50 sticky top-0 border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Hardware ID</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Maintenance Vendor</th>
                            <th class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($maintenanceLaptopsList as $lap)
                            @php
                                $activeRepair = $lap->repairs->first();
                                $diffString = $activeRepair 
                                    ? $activeRepair->sent_date->diffForHumans(['syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE, 'parts' => 2]) 
                                    : 'Unknown';
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-5 font-mono text-sm font-black text-slate-900 tracking-widest">
                                    {{ $lap->serial_number }}
                                </td>
                                <td class="px-8 py-5 text-[10px] text-slate-500 font-black uppercase tracking-widest">
                                    {{ $activeRepair->vendor->value ?? 'Unknown Vendor' }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('laptops.repair_return', $lap) }}" class="inline-flex items-center px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-100 transition-colors group">
                                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        In Queue: {{ $diffString }}
                                        <svg class="w-3 h-3 ml-2 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-8 text-center text-sm font-medium text-slate-400">No hardware in maintenance queue.</td>
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
            const innerBox = modal.querySelector('.max-w-4xl');
            innerBox.classList.add('animate-[fade-in-up_0.3s_ease-out]');
        }
        function closeDashModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                ['modal-available', 'modal-assigned', 'modal-maintenance'].forEach(closeDashModal);
            }
        });
    </script>
    <style>
        @keyframes fade-in-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fade-in-right { from { opacity: 0; transform: translateX(-20px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes fade-in-left { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    </style>
</x-layout>