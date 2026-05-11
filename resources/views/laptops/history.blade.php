<x-layout>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 mt-4 print:p-0 print:mt-0">

        <div
            class="mb-8 flex justify-between items-end border-b border-slate-200 pb-5 print:border-b-2 print:border-slate-800">
            <div>
                <h2 class="text-3xl font-bold text-slate-900">Laptop history</h2>
                <p class="text-sm text-slate-500 mt-1">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('laptops.index') }}"
                    class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 text-sm font-medium transition-colors">&larr;
                    Back</a>

                <a href="{{ route('laptops.history.pdf', $laptop) }}" target="_blank"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-slate-800 text-sm font-medium transition-colors flex items-center">
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
                        <img src="{{ $laptop->photo_data_url }}"
                            class="w-full h-auto object-contain rounded border border-slate-200 bg-white">
                    @else
                        <div
                            class="w-full h-32 bg-slate-200 rounded flex items-center justify-center text-slate-400 text-sm italic">
                            No image</div>
                    @endif
                </div>
                <div class="md:col-span-3 grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Make and model</p>
                        <p class="font-bold text-slate-900">{{ $laptop->brand->value ?? 'N/A' }}</p>
                        <p class="text-sm text-slate-700">{{ $laptop->model->value ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Identification</p>
                        <p class="text-sm text-slate-700"><span class="text-slate-500">SN:</span> <span
                                class="font-mono font-medium text-slate-900">{{ $laptop->serial_number }}</span></p>
                        <p class="text-sm text-slate-700"><span class="text-slate-500">FA:</span>
                            {{ $laptop->laptop_fa_code ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Current status</p>
                        <p class="font-bold text-blue-600 uppercase">{{ $laptop->status }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Processing power</p>
                        <p class="text-sm text-slate-900">{{ $laptop->processor->value ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Memory (RAM)</p>
                        <p class="text-sm text-slate-900">{{ $laptop->ramSize->value ?? 'N/A' }}
                            ({{ $laptop->ram_type }})</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Storage</p>
                        <p class="text-sm text-slate-900">{{ $laptop->storageSize->value ?? 'N/A' }}
                            ({{ $laptop->storage_type }})</p>
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

                    <div
                        class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm print:shadow-none print:border-slate-300">
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
</x-layout>
