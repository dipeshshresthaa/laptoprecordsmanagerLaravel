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
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

                    <!-- Updated Photo Gallery Section -->
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
                    <!-- End Updated Photo Gallery Section -->

                    <div class="p-5">
                        <p class="text-sm font-semibold text-slate-500">
                            {{ $laptop->brand->value ?? 'Unknown Brand' }}
                        </p>
                        <h3 class="text-xl font-bold text-slate-900 mb-6">
                            {{ $laptop->model->value ?? 'Unknown Model' }}
                        </h3>

                        <h4
                            class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">
                            Identification
                        </h4>
                        <div class="space-y-2 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Serial number:</span>
                                <span class="font-medium text-slate-900">{{ $laptop->serial_number }}</span>
                            </div>
                        </div>

                        <h4
                            class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">
                            Hardware specs
                        </h4>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-[11px] text-slate-500">RAM</p>
                                <p class="text-sm font-medium text-slate-900">{{ $laptop->ramSize->value ?? 'N/A' }}</p>
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

            <div class="lg:col-span-2 space-y-8">

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-rose-50/30">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center">
                            <span class="mr-2">🛠️</span> Log repair dispatch
                        </h3>
                    </div>

                    <form action="{{ route('laptops.store_repair', $laptop) }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-6">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Vendor / Repair center
                                        *</label>
                                    <input list="vendorList" name="vendor_name" required
                                        placeholder="Type or select a vendor..." value="{{ old('vendor_name') }}"
                                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 text-sm">
                                    <datalist id="vendorList">
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor }}">
                                        @endforeach
                                    </datalist>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Date sent *</label>
                                    <input type="date" name="sent_date" min="{{ $minDate->format('Y-m-d') }}"
                                        max="{{ now()->format('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                                        class="w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 text-sm">
                                    <p class="text-xs text-slate-500 mt-1">Must be between
                                        {{ $minDate->format('M d, Y') }} and today.</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Issue description *</label>
                                <textarea name="issue_description" required rows="3" placeholder="Describe the hardware failure..."
                                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-rose-500 focus:ring-rose-500 text-sm">{{ old('issue_description') }}</textarea>
                            </div>

                        </div>

                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
                            <a href="{{ route('laptops.index') }}"
                                class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 text-sm font-medium">Cancel</a>
                            <button type="submit"
                                class="px-5 py-2 bg-rose-600 text-white rounded-lg shadow-sm hover:bg-rose-700 text-sm font-medium transition-colors">Confirm
                                dispatch</button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-900">Repair history</h3>
                        <span
                            class="text-xs font-medium bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full">{{ $laptop->repairs->count() }}
                            records</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">
                                        Vendor</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Sent
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">
                                        Returned</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Issue
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($laptop->repairs()->latest('sent_date')->get() as $history)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                            {{ $history->vendor_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                            {{ $history->sent_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                            @if ($history->returned_date)
                                                {{ $history->returned_date->format('M d, Y') }}
                                            @else
                                                <span class="text-rose-600 font-medium">In Repair</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600 truncate max-w-xs"
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

    <!-- Scripts and Styles for the Gallery functionality -->
    <script>
        // --- Image Swapping Logic ---
        function swapImage(newSrc, clickedButton) {
            // Change the main image source
            const mainImage = document.getElementById('main-gallery-image');
            mainImage.src = newSrc;

            // Reset styling on all thumbnail buttons
            const allThumbnails = document.querySelectorAll('.thumbnail-btn');
            allThumbnails.forEach(btn => {
                btn.classList.remove('border-blue-500', 'shadow-md', 'opacity-100');
                btn.classList.add('border-slate-200', 'opacity-70');
            });

            // Highlight the clicked thumbnail
            clickedButton.classList.remove('border-slate-200', 'opacity-70');
            clickedButton.classList.add('border-blue-500', 'shadow-md', 'opacity-100');
        }

        // --- E-commerce Zoom Logic ---
        function zoomImage(event, element) {
            // Get the bounding rectangle of the image container
            const rect = element.parentElement.getBoundingClientRect();

            // Calculate the mouse position relative to the container (0 to 1)
            const x = (event.clientX - rect.left) / rect.width;
            const y = (event.clientY - rect.top) / rect.height;

            // Convert the 0-1 values to percentages for the CSS transform-origin property
            const originX = (x * 100) + '%';
            const originY = (y * 100) + '%';

            // Apply the new origin so the image scales toward the mouse pointer
            element.style.transformOrigin = `${originX} ${originY}`;
        }

        function resetZoom(element) {
            // Reset the origin to the center when the mouse leaves
            element.style.transformOrigin = 'center center';
        }
    </script>

    <style>
        /* Hide scrollbar for the thumbnail row but keep functionality */
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
