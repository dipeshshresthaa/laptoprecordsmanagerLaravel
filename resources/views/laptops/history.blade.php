<x-layout>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 mt-4 print:p-0 print:mt-0">

        <div
            class="mb-8 flex justify-between items-end border-b border-slate-200 pb-5 print:border-b-2 print:border-slate-800">
            <div>
                <h2 class="text-3xl font-bold text-slate-900">Laptop history</h2>
                <p class="text-sm text-slate-500 mt-1">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('laptops.index') }}" class="btn btn-secondary">&larr; Back</a>
                <a href="{{ route('laptops.history.pdf', $laptop) }}" target="_blank"
                    class="btn btn-primary hover:!bg-slate-800 !bg-slate-900">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Download PDF report
                </a>
            </div>
        </div>

        <div
            class="bg-white border border-slate-200 rounded-xl overflow-hidden mb-8 shadow-sm print:border-slate-300 print:bg-transparent print:break-inside-avoid print:shadow-none">
            <div class="flex flex-col md:flex-row">

                <!-- Left Panel: Hardware Photos -->
                <div
                    class="w-full md:w-1/3 bg-slate-50 p-6 border-b md:border-b-0 md:border-r border-slate-200 print:bg-transparent print:p-0 print:border-none">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Hardware photos</h3>

                    <div class="w-full flex flex-col items-center">
                        @if ($laptop->photos && $laptop->photos->count() > 0)
                            @php $firstPhotoUrl = Storage::url($laptop->photos->first()->photo_path); @endphp

                            <!-- Main Image Container -->
                            <a href="{{ $firstPhotoUrl }}" target="_blank" id="main-image-link"
                                class="relative block w-full aspect-square overflow-hidden rounded-lg border border-slate-200 group bg-white mb-4 shadow-sm"
                                title="Click to open in new tab">
                                <img src="{{ $firstPhotoUrl }}" alt="Laptop Main View" id="main-gallery-image"
                                    class="w-full h-full object-contain p-2 transition-transform duration-500 ease-in-out group-hover:scale-[2.5] origin-center cursor-pointer"
                                    onmousemove="zoomImage(event, this)" onmouseleave="resetZoom(this)">
                            </a>

                            <!-- Thumbnails -->
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
                                class="w-full aspect-square bg-white rounded-lg flex flex-col items-center justify-center text-slate-400 border border-slate-200 shadow-sm">
                                <svg class="w-12 h-12 mb-2 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium text-center">No images<br>available</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Panel: Device Details -->
                <div class="w-full md:w-2/3 p-6 sm:p-8 flex flex-col">

                    <!-- Hero: Brand & Model (Replacing the old Device Information div) -->
                    <div class="mb-8 border-b border-slate-100 pb-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span
                                class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] uppercase tracking-widest font-bold rounded-md">
                                {{ $laptop->brand->value ?? 'Unknown Brand' }}
                            </span>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                            {{ $laptop->model->value ?? 'Unknown Model' }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-10 flex-grow">

                        <!-- Column 1: Identity & Status -->
                        <div class="space-y-8">
                            <!-- Identification -->
                            <div class="flex flex-col">
                                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-2">
                                    Identification</p>
                                <div class="space-y-1.5 p-4 bg-slate-50 rounded-lg border border-slate-100">
                                    <p class="text-sm flex justify-between"><span
                                            class="text-slate-500 font-medium">SN:</span> <span
                                            class="font-mono font-bold text-slate-800">{{ $laptop->serial_number }}</span>
                                    </p>
                                    <p class="text-sm flex justify-between"><span class="text-slate-500 font-medium">FA
                                            Code:</span> <span
                                            class="text-slate-700 font-medium">{{ $laptop->laptop_fa_code ?? 'N/A' }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="flex flex-col">
                                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-2">Current
                                    Status</p>
                                <div class="flex flex-col items-start gap-2">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $laptop->status === 'Assigned' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                                        {{ $laptop->status }}
                                    </span>

                                    @if ($laptop->status === 'Assigned' && $laptop->currentAssignment)
                                        <div
                                            class="mt-1 w-full p-3 bg-white rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
                                            <div
                                                class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                                                {{ substr($laptop->currentAssignment->employee?->first_name ?? 'U', 0, 1) }}{{ substr($laptop->currentAssignment->employee?->last_name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <p
                                                    class="text-[10px] text-slate-500 uppercase font-bold leading-none mb-1">
                                                    Assigned to</p>
                                                <p class="text-sm font-bold text-slate-900 truncate">
                                                    {{ $laptop->currentAssignment->employee?->full_name ?? 'Unknown User' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Hardware Specs -->
                        <div class="space-y-6">
                            <h4
                                class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-1 border-b border-slate-100 pb-2">
                                Hardware Specifications</h4>

                            <!-- Processor -->
                            <div class="flex flex-col">
                                <p class="text-[11px] text-slate-500 font-medium mb-1">Processor</p>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                    </svg>
                                    <p class="text-sm font-bold text-slate-800">
                                        {{ $laptop->processor->value ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- RAM -->
                            <div class="flex flex-col">
                                <p class="text-[11px] text-slate-500 font-medium mb-1">Memory (RAM)</p>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm font-bold text-slate-800">{{ $laptop->ramSize->value ?? 'N/A' }}
                                        <span
                                            class="text-[11px] text-slate-500 font-normal ml-1">({{ $laptop->ram_type }})</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Storage -->
                            <div class="flex flex-col">
                                <p class="text-[11px] text-slate-500 font-medium mb-1">Storage Capacity</p>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm font-bold text-slate-800">
                                        {{ $laptop->storageSize->value ?? 'N/A' }}
                                        <span
                                            class="text-[11px] text-slate-500 font-normal ml-1">({{ $laptop->storage_type }})</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <h3 class="text-xl font-bold text-slate-900 mb-6 print:mt-8">Lifecycle timeline</h3>

        <div class="relative border-l-2 border-slate-200 ml-4 print:border-slate-300 space-y-8 pb-12">
            @foreach ($timeline as $event)
                <div class="relative pl-8 print:break-inside-avoid">
                    <div
                        class="absolute -left-5 top-1 h-10 w-10 rounded-full flex items-center justify-center ring-4 ring-white print:ring-0 {{ $event->color }}">
                        <span class="text-lg">{{ $event->icon }}</span>
                    </div>

                    <div class="card p-5 print:shadow-none print:border-slate-300">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span
                                    class="text-xs font-bold uppercase tracking-wider {{ str_replace('bg-', 'text-', explode(' ', $event->color)[0]) }} mb-1 block">
                                    {{ $event->type }}
                                </span>
                                <h4 class="text-lg font-bold text-slate-900">{{ $event->title }}</h4>
                            </div>
                            <span
                                class="text-sm font-medium text-slate-500 bg-slate-50 px-2.5 py-1 rounded border border-slate-100 print:bg-transparent print:border-none">
                                {{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}
                            </span>
                        </div>
                        <p
                            class="text-sm text-slate-600 leading-relaxed mt-2 border-t border-slate-50 pt-2 print:border-slate-200">
                            {{ $event->details }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // --- Image Swapping Logic ---
        function swapImage(newSrc, clickedButton) {
            // Change the main image source
            const mainImage = document.getElementById('main-gallery-image');
            mainImage.src = newSrc;

            // Change the anchor link so clicking it opens the correct image in new tab
            const mainImageLink = document.getElementById('main-image-link');
            if (mainImageLink) mainImageLink.href = newSrc;

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
