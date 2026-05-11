<x-layout>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 mt-4">

        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">
                    {{ $laptop->exists ? 'Edit laptop details' : 'Add new laptop' }}
                </h2>
                @if ($laptop->exists && $laptop->creator)
                    <p class="text-xs text-slate-500 mt-1">Created by {{ $laptop->creator->username }} on
                        {{ $laptop->created_at->format('M d, Y') }}</p>
                @endif
            </div>
            <a href="{{ route('laptops.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">&larr;
                Back to inventory</a>
        </div>

        @if ($laptop->is_disposed)
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6 text-sm text-amber-800">
                ⚠ This record is READ-ONLY because the laptop has been marked as Disposed.
            </div>
        @endif

        <form action="{{ $laptop->exists ? route('laptops.update', $laptop) : route('laptops.store') }}" method="POST"
            enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @csrf
            @if ($laptop->exists)
                @method('PUT')
            @endif

            @php $disabled = $laptop->is_disposed ? 'disabled' : ''; @endphp

            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Identification</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Serial number *</label>
                        <input type="text" name="serial_number"
                            value="{{ old('serial_number', $laptop->serial_number) }}" required {{ $disabled }}
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Service tag</label>
                        <input type="text" name="service_tag" value="{{ old('service_tag', $laptop->service_tag) }}"
                            {{ $disabled }}
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">FA code</label>
                        <input type="text" name="laptop_fa_code"
                            value="{{ old('laptop_fa_code', $laptop->laptop_fa_code) }}" {{ $disabled }}
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Make and model</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Brand *</label>
                        <select name="brand_id" id="brandSelect" required {{ $disabled }}
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Select brand --</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id', $laptop->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Model *</label>
                        <select name="model_id" id="modelSelect" required {{ $disabled }}
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Select brand first --</option>
                            @foreach ($models as $model)
                                <option value="{{ $model->id }}"
                                    {{ old('model_id', $laptop->model_id) == $model->id ? 'selected' : '' }}>
                                    {{ $model->value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Hardware specifications</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-slate-700">Processor</label>
                        <select name="processor_id" {{ $disabled }}
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Select --</option>
                            @foreach ($processors as $proc)
                                <option value="{{ $proc->id }}"
                                    {{ old('processor_id', $laptop->processor_id) == $proc->id ? 'selected' : '' }}>
                                    {{ $proc->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">RAM capacity</label>
                        <select name="ram_size_id" {{ $disabled }}
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Select --</option>
                            @foreach ($ramSizes as $ram)
                                <option value="{{ $ram->id }}"
                                    {{ old('ram_size_id', $laptop->ram_size_id) == $ram->id ? 'selected' : '' }}>
                                    {{ $ram->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">RAM type</label>
                        <select name="ram_type" {{ $disabled }}
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Select --</option>
                            @foreach ($ramTypes as $type)
                                <option value="{{ $type }}"
                                    {{ old('ram_type', $laptop->ram_type) == $type ? 'selected' : '' }}>
                                    {{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Screen size</label>
                        <select name="screen_size_id" {{ $disabled }}
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Select --</option>
                            @foreach ($screenSizes as $screen)
                                <option value="{{ $screen->id }}"
                                    {{ old('screen_size_id', $laptop->screen_size_id) == $screen->id ? 'selected' : '' }}>
                                    {{ $screen->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Storage capacity</label>
                        <select name="storage_size_id" {{ $disabled }}
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Select --</option>
                            @foreach ($storageSizes as $storage)
                                <option value="{{ $storage->id }}"
                                    {{ old('storage_size_id', $laptop->storage_size_id) == $storage->id ? 'selected' : '' }}>
                                    {{ $storage->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Storage type</label>
                        <select name="storage_type" {{ $disabled }}
                            class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Select --</option>
                            @foreach ($storageTypes as $type)
                                <option value="{{ $type }}"
                                    {{ old('storage_type', $laptop->storage_type) == $type ? 'selected' : '' }}>
                                    {{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Status and media</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Purchase date *</label>
                            <input type="date" name="purchase_date" 
                                   max="{{ now()->format('Y-m-d') }}" 
                                   value="{{ old('purchase_date', $laptop->purchase_date ? $laptop->purchase_date->format('Y-m-d') : now()->format('Y-m-d')) }}" 
                                   required {{ $disabled }} class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        {{-- <div>
                            <label class="block text-sm font-medium text-slate-700">Status</label>
                            <select name="status" required {{ $disabled }}
                                class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}"
                                        {{ old('status', $laptop->status) == $status ? 'selected' : '' }}>
                                        {{ $status }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                            <textarea name="laptop_identifier_notes" rows="3" {{ $disabled }}
                                class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('laptop_identifier_notes', $laptop->laptop_identifier_notes) }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Laptop photo (Max 2MB)</label>
                        <input type="file" name="photo" accept=".jpg,.jpeg,.png" {{ $disabled }}
                            class="mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                        @if ($laptop->laptop_photo)
                            <div class="mt-4 p-2 bg-slate-50 rounded-lg border border-slate-200 inline-block">
                                <img src="{{ $laptop->photo_data_url }}" alt="Laptop Photo"
                                    class="h-40 rounded shadow-sm object-cover">
                                <p class="text-xs text-slate-500 mt-2 text-center">Current image stored in database</p>
                            </div>
                        @else
                            <div
                                class="mt-4 p-8 bg-slate-50 rounded-lg border border-dashed border-slate-300 flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-sm">No photo uploaded</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if (!$laptop->is_disposed)
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
                    <a href="{{ route('laptops.index') }}"
                        class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 text-sm font-medium transition-colors">Cancel</a>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 text-sm font-medium transition-colors">Save
                        laptop</button>
                </div>
            @endif
        </form>
    </div>

    <script>
        document.getElementById('brandSelect').addEventListener('change', function() {
            const brandId = this.value;
            const modelSelect = document.getElementById('modelSelect');

            modelSelect.innerHTML = '<option value="">Loading...</option>';

            if (!brandId) {
                modelSelect.innerHTML = '<option value="">-- Select brand first --</option>';
                return;
            }

            fetch(`/api/lookups/models/${brandId}`)
                .then(response => response.json())
                .then(data => {
                    modelSelect.innerHTML = '<option value="">-- Select model --</option>';
                    data.forEach(model => {
                        modelSelect.innerHTML += `<option value="${model.id}">${model.value}</option>`;
                    });
                })
                .catch(error => {
                    modelSelect.innerHTML = '<option value="">Error loading models</option>';
                });
        });
    </script>
</x-layout>
