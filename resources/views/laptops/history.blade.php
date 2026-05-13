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
            class="bg-slate-50 border border-slate-200 rounded-xl p-6 mb-8 print:border-slate-300 print:bg-transparent print:break-inside-avoid">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:col-span-1">
                    @if ($laptop->laptop_photo)
                        <div class="relative group cursor-zoom-in overflow-hidden rounded-lg border border-slate-200 bg-white"
                            onclick="openImageModal('{{ $laptop->photo_data_url }}')">
                            <img src="{{ $laptop->photo_data_url }}"
                                class="w-full h-auto object-contain transition-transform duration-300 group-hover:scale-105"
                                alt="Laptop Photo">
                            <div
                                class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/10 transition-colors flex items-center justify-center">
                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                </svg>
                            </div>
                        </div>
                    @else
                        <div
                            class="w-full h-32 bg-slate-100 rounded-lg border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-8 h-8 mb-1 opacity-50" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-xs font-medium uppercase tracking-wider">No Photo</span>
                        </div>
                    @endif
                </div>

                <div id="imageZoomModal"
                    class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/90 backdrop-blur-sm p-4"
                    onclick="closeImageModal()">
                    <div class="relative max-w-5xl w-full h-full flex items-center justify-center">
                        <img id="zoomedImage" src=""
                            class="max-w-full max-h-full rounded-lg shadow-2xl object-contain">
                        <button class="absolute top-0 right-0 m-4 text-white hover:text-slate-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="md:col-span-3 grid grid-cols-2 md:grid-cols-3 gap-y-8 gap-x-6">
                    <div class="flex flex-col">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-2">Device
                            Information</p>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-slate-100 rounded-lg text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 leading-tight">{{ $laptop->brand->value ?? 'N/A' }}
                                </p>
                                <p class="text-sm text-slate-600">{{ $laptop->model->value ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-2">Identification
                        </p>
                        <div class="space-y-1">
                            <p class="text-sm"><span class="text-slate-400 font-medium">SN:</span> <span
                                    class="font-mono font-bold text-slate-800">{{ $laptop->serial_number }}</span></p>
                            <p class="text-sm"><span class="text-slate-400 font-medium">FA:</span> <span
                                    class="text-slate-700 font-medium">{{ $laptop->laptop_fa_code ?? 'N/A' }}</span></p>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-2">Current Status
                        </p>
                        <div class="flex flex-col gap-1">
                            <span
                                class="inline-flex items-center w-fit px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $laptop->status === 'Assigned' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $laptop->status }}
                            </span>

                            @if ($laptop->status === 'Assigned' && $laptop->currentAssignment)
                                <div class="mt-2 p-2 bg-blue-50 rounded-lg border border-blue-100">
                                    <p class="text-[9px] text-blue-500 uppercase font-bold leading-none mb-1">User</p>
                                    <p class="text-xs font-bold text-blue-900 truncate">
                                        {{ $laptop->currentAssignment->employee?->full_name ?? 'Unknown User' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-2">Processor</p>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            <p class="text-sm font-semibold text-slate-800">{{ $laptop->processor->value ?? 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-2">Memory (RAM)</p>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm font-semibold text-slate-800">{{ $laptop->ramSize->value ?? 'N/A' }}
                                <span
                                    class="text-[10px] text-slate-500 font-normal ml-1">({{ $laptop->ram_type }})</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-2">Storage</p>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-sm font-semibold text-slate-800">{{ $laptop->storageSize->value ?? 'N/A' }}
                                <span
                                    class="text-[10px] text-slate-500 font-normal ml-1">({{ $laptop->storage_type }})</span>
                            </p>
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
        function openImageModal(src) {
            const modal = document.getElementById('imageZoomModal');
            document.getElementById('zoomedImage').src = src;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageZoomModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeImageModal();
        });
    </script>
</x-layout>
