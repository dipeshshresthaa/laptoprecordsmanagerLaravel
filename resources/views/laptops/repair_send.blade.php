<x-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 mt-4">

        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Send to repair</h2>
                <p class="text-sm text-slate-500 mt-1">Log external hardware maintenance and status updates.</p>
            </div>
            <a href="{{ route('laptops.index') }}"
                class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">&larr; Back to
                inventory</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <div class="lg:col-span-1 space-y-6">
                <div class="card">
                    <div class="bg-slate-50 border-b border-slate-100 flex items-center justify-center p-6">
                        <div class="w-full max-w-sm flex flex-col items-center">
                            @if ($laptop->photos && $laptop->photos->count() > 0)
                                <div class="relative w-full aspect-square overflow-hidden rounded-lg border border-slate-200 group bg-white mb-4 shadow-sm"
                                    id="main-image-container">
                                    @php $firstPhotoUrl = Storage::url($laptop->photos->first()->photo_path); @endphp
                                    <img src="{{ $firstPhotoUrl }}" alt="Laptop Main View" id="main-gallery-image"
                                        class="w-full h-full object-contain p-2 transition-transform duration-500 ease-in-out group-hover:scale-[2.2] origin-center cursor-crosshair"
                                        onmousemove="zoomImage(event, this)" onmouseleave="resetZoom(this)">
                                </div>

                                @if ($laptop->photos->count() > 1)
                                    <div class="flex gap-2 w-full overflow-x-auto pb-2 custom-scrollbar justify-center">
                                        @foreach ($laptop->photos as $index => $photo)
                                            @php $photoUrl = Storage::url($photo->photo_path); @endphp
                                            <button type="button" onclick="swapImage('{{ $photoUrl }}', this)"
                                                class="thumbnail-btn flex-shrink-0 w-16 h-16 bg-white rounded border-2 overflow-hidden transition-all {{ $index === 0 ? 'border-blue-500 shadow-md' : 'border-slate-200 hover:border-slate-400 opacity-70 hover:opacity-100' }}">
                                                <img src="{{ $photoUrl }}" alt="Thumbnail {{ $index + 1 }}"
                                                    class="w-full h-full object-cover">
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div
                                    class="w-full aspect-square bg-slate-100 rounded-lg flex flex-col items-center justify-center text-slate-400 border border-slate-200 shadow-sm">
                                    <svg class="w-12 h-12 mb-2 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium">No images available</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-5">
                        <p class="text-sm font-semibold text-slate-500">{{ $laptop->brand->value ?? 'Unknown Brand' }}
                        </p>
                        <h3 class="text-xl font-bold text-slate-900 mb-6">{{ $laptop->model->value ?? 'Unknown Model' }}
                        </h3>

                        <h4
                            class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">
                            Identification</h4>
                        <div class="space-y-2 mb-6 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Serial number:</span>
                                <span class="font-medium text-slate-900 font-mono">{{ $laptop->serial_number }}</span>
                            </div>
                        </div>

                        <h4
                            class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">
                            Hardware specs</h4>
                        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                            <div>
                                <p class="text-[11px] text-slate-500">RAM</p>
                                <p class="font-medium text-slate-900">{{ $laptop->ramSize->value ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-500">Storage</p>
                                <p class="font-medium text-slate-900">{{ $laptop->storageSize->value ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">

                <div class="card">
                    <div class="card-header bg-rose-50/30 text-rose-900">
                        <h3 class="card-title flex items-center">
                            <span class="mr-2">🛠️</span> Log repair dispatch
                        </h3>
                    </div>

                    <form action="{{ route('laptops.store_repair', $laptop) }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-6">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="form-label !mb-0">Vendor / Repair center *</label>
                                        <button type="button" onclick="openLookupModal('Vendor')"
                                            class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ New</button>
                                    </div>
                                    <select name="vendor_id" id="Vendor_select" required class="form-input">
                                        <option value="">-- Select Vendor --</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}"
                                                {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vendor_id')
                                        <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">Date sent *</label>
                                    <input type="date" name="sent_date" min="{{ $minDate->format('Y-m-d') }}"
                                        max="{{ now()->format('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                                        class="form-input focus:border-rose-500 focus:ring-rose-500">
                                    <p class="text-xs text-slate-500 mt-1">Must be between
                                        {{ $minDate->format('M d, Y') }} and today.</p>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Issue description *</label>
                                <textarea name="issue_description" required rows="3" placeholder="Describe the hardware failure..."
                                    class="form-input focus:border-rose-500 focus:ring-rose-500">{{ old('issue_description') }}</textarea>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
                            <a href="{{ route('laptops.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn !bg-rose-600 !text-white hover:!bg-rose-700">Confirm
                                dispatch</button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header flex justify-between items-center">
                        <h3 class="card-title">Repair history</h3>
                        <span
                            class="text-xs font-medium bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full">{{ $laptop->repairs->count() }}
                            records</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-base">
                            <thead class="table-head">
                                <tr>
                                    <th class="table-th">Vendor</th>
                                    <th class="table-th">Sent</th>
                                    <th class="table-th">Returned</th>
                                    <th class="table-th">Issue</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($laptop->repairs()->latest('sent_date')->get() as $history)
                                    <tr class="table-row">
                                        <td class="table-td font-medium">
                                            {{ $history->vendor->value ?? ($history->vendor_name ?? 'Unknown Vendor') }}
                                        </td>
                                        <td class="table-td text-slate-600">
                                            {{ $history->sent_date->format('M d, Y') }}
                                        </td>
                                        <td class="table-td text-slate-600">
                                            @if ($history->returned_date)
                                                {{ $history->returned_date->format('M d, Y') }}
                                            @else
                                                <span class="text-rose-600 font-bold">In Repair</span>
                                            @endif
                                        </td>
                                        <td class="table-td text-slate-600 truncate max-w-xs"
                                            title="{{ $history->issue_description }}">
                                            {{ Str::limit($history->issue_description, 40) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-8 text-center text-sm text-slate-500 italic">
                                            No prior repair history found for this device.
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

    <div id="lookupModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl p-6 shadow-xl w-80">
            <h3 class="text-sm font-bold text-slate-900 uppercase mb-4">Add <span id="lookupTitle"></span></h3>
            <input type="hidden" id="lookupCategory">
            <input type="text" id="lookupValue" class="form-input mb-4" placeholder="Enter vendor name...">
            <div class="flex space-x-2">
                <button type="button" onclick="closeLookupModal()" class="btn btn-secondary flex-1">Cancel</button>
                <button type="button" onclick="submitLookup()" class="btn btn-primary flex-1">Save</button>
            </div>
        </div>
    </div>

    <script>
        // --- Lookup Modal Logic ---
        function openLookupModal(category) {
            document.getElementById('lookupCategory').value = category;
            document.getElementById('lookupTitle').innerText = category;
            document.getElementById('lookupModal').classList.remove('hidden');
            document.getElementById('lookupModal').classList.add('flex');
            document.getElementById('lookupValue').focus();
        }

        function closeLookupModal() {
            document.getElementById('lookupModal').classList.add('hidden');
            document.getElementById('lookupModal').classList.remove('flex');
            document.getElementById('lookupValue').value = '';
        }

        async function submitLookup() {
            const category = document.getElementById('lookupCategory').value;
            const value = document.getElementById('lookupValue').value.trim();

            if (!value) return;

            try {
                const response = await fetch("{{ route('lookups.quick-store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        category: category,
                        value: value
                    })
                });

                const data = await response.json();
                if (response.ok) {
                    const select = document.getElementById(category + '_select');
                    if (select) {
                        select.add(new Option(data.value, data.id, true, true));
                    }
                    closeLookupModal();
                } else {
                    alert(data.message || 'Error: This entry might already exist.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('A connection error occurred.');
            }
        }

        // --- Image Swapping Logic ---
        function swapImage(newSrc, clickedButton) {
            const mainImage = document.getElementById('main-gallery-image');
            mainImage.src = newSrc;

            const allThumbnails = document.querySelectorAll('.thumbnail-btn');
            allThumbnails.forEach(btn => {
                btn.classList.remove('border-blue-500', 'shadow-md', 'opacity-100');
                btn.classList.add('border-slate-200', 'opacity-70');
            });

            clickedButton.classList.remove('border-slate-200', 'opacity-70');
            clickedButton.classList.add('border-blue-500', 'shadow-md', 'opacity-100');
        }

        // --- E-commerce Zoom Logic ---
        function zoomImage(event, element) {
            const rect = element.parentElement.getBoundingClientRect();
            const x = (event.clientX - rect.left) / rect.width;
            const y = (event.clientY - rect.top) / rect.height;

            const originX = (x * 100) + '%';
            const originY = (y * 100) + '%';

            element.style.transformOrigin = `${originX} ${originY}`;
        }

        function resetZoom(element) {
            element.style.transformOrigin = 'center center';
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</x-layout>
