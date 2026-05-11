<x-layout>
    <div class="max-w-3xl mx-auto p-4 sm:p-6 mt-8">
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Process laptop return</h2>
                <p class="text-sm text-slate-500 mt-1">Hardware ID: <span
                        class="font-mono font-medium text-slate-800">{{ $laptop->serial_number }}</span></p>
            </div>
            <a href="{{ route('laptops.index') }}"
                class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">&larr; Back to inventory</a>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 mb-6">
            <h3 class="text-sm font-bold text-blue-900 mb-3 uppercase tracking-wider">Current custodian</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-blue-600 mb-1">Assigned employee</p>
                    <p class="font-medium text-slate-900">{{ $activeAssignment->employee->first_name }}
                        {{ $activeAssignment->employee->last_name }}</p>
                    <p class="text-sm text-slate-600">{{ $activeAssignment->employee->department ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-blue-600 mb-1">Assigned date</p>
                    <p class="font-medium text-slate-900">{{ $activeAssignment->assigned_date->format('M d, Y') }}</p>
                    <p class="text-sm text-slate-600">{{ $activeAssignment->assigned_date->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('laptops.store_return', $laptop) }}" method="POST"
            class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @csrf

            <div class="p-6 space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Return date *</label>
                        <input type="date" name="returned_date"
                            min="{{ $activeAssignment->assigned_date->format('Y-m-d') }}"
                            max="{{ now()->format('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Next laptop status *</label>
                        <select name="next_status" required
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors">
                            <option value="Available">Available (Ready for next user)</option>
                            <option value="In repair">In repair (Needs maintenance)</option>
                            <option value="Disposed">Disposed (End of lifecycle)</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-1.5">Where is the laptop going now?</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Physical condition *</label>
                    <select name="return_condition" required
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors">
                        <option value="">-- Select condition --</option>
                        <option value="Excellent">Excellent (No signs of wear)</option>
                        <option value="Good">Good (Normal wear and tear)</option>
                        <option value="Fair">Fair (Heavy wear, fully functional)</option>
                        <option value="Poor">Poor (Damaged, partially functional)</option>
                        <option value="Broken">Broken (Requires immediate repair/disposal)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Return reason / Additional
                        notes</label>
                    <textarea name="return_reason" rows="3" placeholder="e.g. Employee resigned, laptop upgrade, damaged screen..."
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm transition-colors">{{ old('return_reason') }}</textarea>
                </div>

            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    A PDF receipt will be generated automatically.
                </span>

                <div class="flex space-x-3">
                    <a href="{{ route('laptops.index') }}"
                        class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 text-sm font-medium transition-colors">Cancel</a>
                    <button type="submit"
                        class="px-5 py-2 bg-amber-500 text-white rounded-lg shadow-sm hover:bg-amber-600 text-sm font-medium transition-colors">Process
                        return</button>
                </div>
            </div>
        </form>
    </div>
</x-layout>
