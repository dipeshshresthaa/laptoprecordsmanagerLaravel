<x-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 mt-4">

        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Log hardware upgrades</h2>
                <p class="text-sm text-slate-500 mt-1">Record component upgrades and update the current system specs.</p>
            </div>
            <a href="{{ route('laptops.index') }}"
                class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">&larr; Back to
                inventory</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
                    <div class="h-48 bg-slate-50 border-b border-slate-100 flex items-center justify-center p-4">
                        @if ($laptop->laptop_photo)
                            <img src="{{ $laptop->photo_data_url }}" alt="Laptop"
                                class="max-h-full object-contain rounded">
                        @else
                            <span class="text-slate-400 italic text-sm">No image available</span>
                        @endif
                    </div>
                    <div class="p-5">
                        <p class="text-sm font-semibold text-slate-500">{{ $laptop->brand->value ?? 'Unknown Brand' }}
                        </p>
                        <h3 class="text-xl font-bold text-slate-900 mb-6">{{ $laptop->model->value ?? 'Unknown Model' }}
                        </h3>

                        <h4
                            class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">
                            Identification</h4>
                        <div class="space-y-2 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Serial number:</span>
                                <span class="font-medium text-slate-900">{{ $laptop->serial_number }}</span>
                            </div>
                        </div>

                        <h4
                            class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">
                            Current hardware</h4>
                        <div class="space-y-3 mb-2">
                            <div>
                                <p class="text-[11px] text-slate-500">Processor</p>
                                <p class="text-sm font-medium text-slate-900">{{ $laptop->processor->value ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[11px] text-slate-500">RAM</p>
                                    <p class="text-sm font-medium text-slate-900">{{ $laptop->ramSize->value ?? 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[11px] text-slate-500">Storage</p>
                                    <p class="text-sm font-medium text-slate-900">
                                        {{ $laptop->storageSize->value ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-emerald-50/30">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center">
                            <span class="mr-2">⚙️</span> Process component upgrade
                        </h3>
                    </div>

                    <form action="{{ route('laptops.store_upgrade', $laptop) }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-6">

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-slate-50 border border-slate-200 rounded-lg">
                                <div>
                                    <label class="block text-sm font-bold text-slate-800 mb-1">What is being upgraded?
                                        *</label>
                                    <select name="upgrade_type" id="upgradeTypeSelect" required
                                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                        <option value="">-- Select component --</option>
                                        <option value="RAM">RAM / Memory</option>
                                        <option value="Storage">Storage / Hard drive</option>
                                        <option value="Processor">Processor / CPU</option>
                                    </select>
                                </div>

                                <div id="ramContainer" class="hidden spec-container">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Select new RAM size
                                        *</label>
                                    <select name="new_ram_id"
                                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                        <option value="">-- Select --</option>
                                        @foreach ($ramSizes as $ram)
                                            <option value="{{ $ram->id }}">{{ $ram->value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="storageContainer" class="hidden spec-container">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Select new storage size
                                        *</label>
                                    <select name="new_storage_id"
                                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                        <option value="">-- Select --</option>
                                        @foreach ($storageSizes as $storage)
                                            <option value="{{ $storage->id }}">{{ $storage->value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="processorContainer" class="hidden spec-container">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Select new processor
                                        *</label>
                                    <select name="new_processor_id"
                                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                        <option value="">-- Select --</option>
                                        @foreach ($processors as $cpu)
                                            <option value="{{ $cpu->id }}">{{ $cpu->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Upgrade date *</label>
                                    <input type="date" name="upgrade_date"
                                        min="{{ $laptop->purchase_date->format('Y-m-d') }}"
                                        max="{{ now()->format('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Cost of upgrade
                                        (Optional)</label>
                                    <div class="relative">
                                        <div
                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-slate-500 sm:text-sm"></span>
                                        </div>
                                        <input type="number" name="cost" step="0.01" min="0"
                                            placeholder="0.00"
                                            class="block w-full rounded-lg border-slate-300 pl-7 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Notes / Reason
                                    (Optional)</label>
                                <textarea name="notes" rows="2" placeholder="e.g. Employee requested more RAM for speed..."
                                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">{{ old('notes') }}</textarea>
                            </div>

                        </div>

                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
                            <a href="{{ route('laptops.index') }}"
                                class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 text-sm font-medium">Cancel</a>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm font-medium transition-all">Apply
                                upgrade</button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-900">Upgrade log</h3>
                        <span
                            class="text-xs font-medium bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full">{{ $laptop->upgrades->count() }}
                            records</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">
                                        Component</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">
                                        Change</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Cost
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($laptop->upgrades()->latest('upgrade_date')->get() as $history)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-900">
                                            {{ $history->upgrade_type }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                            <span
                                                class="text-rose-500 line-through mr-1">{{ $history->previous_spec }}</span>
                                            <span class="text-emerald-600 font-medium ml-1">&rarr;
                                                {{ $history->new_spec }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                            {{ $history->upgrade_date->format('M d, yyyy') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600">
                                            {{ $history->cost ? '$' . number_format($history->cost, 2) : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-8 text-center text-sm text-slate-500 italic">
                                            No upgrades have been logged for this device.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('upgradeTypeSelect').addEventListener('change', function() {
            // Hide all containers and remove 'required'
            document.querySelectorAll('.spec-container').forEach(el => {
                el.classList.add('hidden');
                el.querySelector('select').removeAttribute('required');
            });

            // Show the specific container based on selection and add 'required'
            if (this.value === 'RAM') {
                const container = document.getElementById('ramContainer');
                container.classList.remove('hidden');
                container.querySelector('select').setAttribute('required', 'required');
            } else if (this.value === 'Storage') {
                const container = document.getElementById('storageContainer');
                container.classList.remove('hidden');
                container.querySelector('select').setAttribute('required', 'required');
            } else if (this.value === 'Processor') {
                const container = document.getElementById('processorContainer');
                container.classList.remove('hidden');
                container.querySelector('select').setAttribute('required', 'required');
            }
        });
    </script>
</x-layout>
