<x-layout>
    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 mt-0">

        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Mark laptop as disposed</h2>
                <p class="text-sm text-slate-500 mt-1">Hardware ID: <span
                        class="font-mono text-slate-800">{{ $laptop->serial_number }}</span></p>
            </div>
            <a href="{{ route('laptops.index') }}"
                class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">&larr; Back to
                inventory</a>
        </div>

        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r mb-6 shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-rose-800">Permanent action</h3>
                    <div class="mt-1 text-sm text-rose-700">
                        <p>Marking a laptop as disposed is an end-of-lifecycle event. The laptop will become Read-Only.
                        </p>
                        <ul class="list-disc pl-5 mt-2 space-y-1">
                            @if ($activeAssignment)
                                <li><strong>Auto-Close:</strong> {{ $activeAssignment->employee->first_name }}'s active
                                    assignment will be closed automatically.</li>
                            @endif
                            @if ($activeRepair)
                                <li><strong>Auto-Close:</strong> The active repair ticket with
                                    {{ $activeRepair->vendor_name }} will be closed automatically.</li>
                            @endif
                            @if (!$activeAssignment && !$activeRepair)
                                <li>This laptop is currently Available.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('laptops.store_dispose', $laptop) }}" method="POST"
            class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @csrf
            <input type="hidden" name="min_date" value="{{ $minDate->format('Y-m-d') }}">

            <div class="p-6 space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Disposal date *</label>
                        <input type="date" name="disposal_date" min="{{ $minDate->format('Y-m-d') }}"
                            max="{{ now()->format('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Disposal method *</label>
                        <select name="disposal_method" required
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 text-sm">
                            <option value="">-- Select method --</option>
                            <option value="E-Waste recycled">E-Waste recycled</option>
                            <option value="Sold / auctioned">Sold / Auctioned</option>
                            <option value="Lost / Stolen">Lost / Stolen</option>
                            <option value="Destroyed">Destroyed (Irreparable damage)</option>
                            <option value="Donated">Donated</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Detailed reason / Notes *</label>
                    <textarea name="disposal_reason" required rows="3"
                        placeholder="Provide full context for why this asset is leaving the inventory..."
                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 text-sm">{{ old('disposal_reason') }}</textarea>
                </div>

            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
                <a href="{{ route('laptops.index') }}"
                    class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 text-sm font-medium transition-colors">Cancel</a>
                <button type="submit"
                    class="px-5 py-2 bg-rose-600 text-white rounded-lg shadow-sm hover:bg-rose-700 text-sm font-medium transition-colors">Confirm
                    permanent disposal</button>
            </div>
        </form>
    </div>
</x-layout>
