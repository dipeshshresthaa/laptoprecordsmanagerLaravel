<x-layout>
    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 mt-4">

        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Receive from repair</h2>
                <p class="text-sm text-slate-500 mt-1">Hardware ID: <span
                        class="font-mono text-slate-800">{{ $laptop->serial_number }}</span></p>
            </div>
            <a href="{{ route('laptops.index') }}"
                class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">&larr; Back to
                inventory</a>
        </div>

        <div class="bg-rose-50 border border-rose-100 rounded-xl p-5 mb-6">
            <h3 class="text-sm font-bold text-rose-900 mb-3 uppercase tracking-wider">Active repair details</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-rose-600 mb-1">Vendor</p>
                    <p class="font-medium text-slate-900">
                        {{ $activeRepair->vendor->value ?? ($activeRepair->vendor_name ?? 'Unknown Vendor') }}</p>
                </div>
                <div>
                    <p class="text-xs text-rose-600 mb-1">Sent date</p>
                    <p class="font-medium text-slate-900">{{ $activeRepair->sent_date->format('M d, Y') }}</p>
                    <p class="text-[11px] text-slate-600">{{ $activeRepair->sent_date->diffForHumans() }}</p>
                </div>
                <div>
                    <p class="text-xs text-rose-600 mb-1">Issue</p>
                    <p class="text-sm text-slate-900 line-clamp-2" title="{{ $activeRepair->issue_description }}">
                        {{ $activeRepair->issue_description }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('laptops.store_repair_return', $laptop) }}" method="POST" class="card">
            @csrf
            <div class="p-6 space-y-8">

                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-2">1. Post-repair
                        destination</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label
                            class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-slate-200 hover:bg-slate-50 transition-colors has-[:checked]:border-blue-500 has-[:checked]:ring-1 has-[:checked]:ring-blue-500">
                            <input type="radio" name="return_destination" value="Office" class="sr-only" required
                                id="radioOffice" onchange="toggleWarning()">
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium text-slate-900">🏢 Return to Office</span>
                                    <span class="mt-1 flex items-center text-xs text-slate-500">Device will be marked as
                                        'Available'.</span>
                                </span>
                            </span>
                            <svg class="h-5 w-5 text-blue-600 hidden" fill="currentColor" viewBox="0 0 20 20"
                                id="iconOffice">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </label>

                        @if ($activeAssignment)
                            <label
                                class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none border-slate-200 hover:bg-slate-50 transition-colors has-[:checked]:border-blue-500 has-[:checked]:ring-1 has-[:checked]:ring-blue-500">
                                <input type="radio" name="return_destination" value="Employee" class="sr-only"
                                    required id="radioEmployee" onchange="toggleWarning()">
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-slate-900">👨‍💻 Return to
                                            employee</span>
                                        <span class="mt-1 flex items-center text-xs text-slate-500">Back to
                                            {{ $activeAssignment->employee->first_name }}
                                            {{ $activeAssignment->employee->last_name }}.</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-blue-600 hidden" fill="currentColor" viewBox="0 0 20 20"
                                    id="iconEmployee">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </label>
                        @else
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 opacity-75">
                                <span class="block text-sm font-medium text-slate-500">👨‍💻 Return to employee</span>
                                <span class="mt-1 flex items-center text-xs text-slate-400">Disabled: Laptop was not
                                    assigned to an employee prior to repair.</span>
                            </div>
                        @endif
                    </div>

                    @if ($activeAssignment)
                        <div id="officeWarning"
                            class="hidden mt-3 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r text-sm text-amber-800">
                            <strong>Note:</strong> Selecting 'Return to office' will automatically close
                            {{ $activeAssignment->employee->first_name }}'s assignment retroactively on
                            <strong>{{ $activeRepair->sent_date->format('M d, Y') }}</strong> (the date it was sent to
                            the vendor).
                        </div>
                    @endif
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-2">2. Repair resolution
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Date received back *</label>
                            <input type="date" name="returned_date"
                                min="{{ $activeRepair->sent_date->format('Y-m-d') }}"
                                max="{{ now()->format('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                                class="form-input">
                        </div>

                        <div>
                            <label class="form-label">Repair cost (Optional)</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                </div>
                                <input type="number" name="repair_cost" step="0.01" min="0"
                                    placeholder="0.00" class="form-input pl-9">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Repair notes / resolution *</label>
                        <textarea name="repair_notes" required rows="3"
                            placeholder="What was fixed? e.g. Motherboard replaced, screen fixed under warranty..." class="form-input">{{ old('repair_notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
                <a href="{{ route('laptops.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2">Process receipt</button>
            </div>
        </form>
    </div>

    <script>
        function toggleWarning() {
            const isOffice = document.getElementById('radioOffice').checked;
            const officeWarning = document.getElementById('officeWarning');
            const iconOffice = document.getElementById('iconOffice');

            const radioEmployee = document.getElementById('radioEmployee');
            const iconEmployee = document.getElementById('iconEmployee');

            if (officeWarning) {
                officeWarning.style.display = isOffice ? 'block' : 'none';
            }

            // Toggle UI Checkmarks
            iconOffice.style.display = isOffice ? 'block' : 'none';
            if (iconEmployee) iconEmployee.style.display = !isOffice ? 'block' : 'none';
        }
    </script>
</x-layout>
