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

        @if ($errors->any())
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-rose-800">Please fix the following errors:</h3>
                        <ul class="mt-2 text-sm text-rose-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ $laptop->exists ? route('laptops.update', $laptop) : route('laptops.store') }}" method="POST"
            enctype="multipart/form-data" class="card">
            @csrf
            @if ($laptop->exists)
                @method('PUT')
            @endif

            @php $disabled = $laptop->is_disposed ? 'disabled' : ''; @endphp

            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Identification</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="form-label">Serial number *</label>
                        <input type="text" name="serial_number"
                            value="{{ old('serial_number', $laptop->serial_number) }}" required {{ $disabled }}
                            class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Service tag</label>
                        <input type="text" name="service_tag" value="{{ old('service_tag', $laptop->service_tag) }}"
                            {{ $disabled }} class="form-input">
                    </div>
                    <div>
                        <label class="form-label">FA code</label>
                        <input type="text" name="laptop_fa_code" id="fa_code_input"
                            value="{{ old('laptop_fa_code', $laptop->laptop_fa_code) }}" {{ $disabled }}
                            class="form-input">
                        <div id="fa_suggestions" class="mt-1 flex flex-wrap gap-1"></div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Make and model</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label !mb-0">Brand *</label>
                            <button type="button" onclick="openLookupModal('Brand')"
                                class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ New</button>
                        </div>
                        <select name="brand_id" id="Brand_select" required {{ $disabled }} class="form-input">
                            <option value="">-- Select brand --</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id', $laptop->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label !mb-0">Model *</label>
                            <button type="button" onclick="openLookupModal('Model')"
                                class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ New</button>
                        </div>
                        <select name="model_id" id="Model_select" required {{ $disabled }} class="form-input">
                            <option value="">-- Select brand first --</option>
                            @foreach ($models as $model)
                                <option value="{{ $model->id }}"
                                    {{ old('model_id', $laptop->model_id) == $model->id ? 'selected' : '' }}>
                                    {{ $model->value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Hardware
                    specifications</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="md:col-span-3">
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label !mb-0">Processor *</label>
                            <button type="button" onclick="openLookupModal('Processor')"
                                class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ New</button>
                        </div>
                        <select name="processor_id" id="Processor_select" {{ $disabled }} class="form-input">
                            <option value="">-- Select --</option>
                            @foreach ($processors as $proc)
                                <option value="{{ $proc->id }}"
                                    {{ old('processor_id', $laptop->processor_id) == $proc->id ? 'selected' : '' }}>
                                    {{ $proc->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label !mb-0">RAM capacity*</label>
                            <button type="button" onclick="openLookupModal('RamSize')"
                                class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ New</button>
                        </div>
                        <select name="ram_size_id" id="RamSize_select" {{ $disabled }} class="form-input">
                            <option value="">-- Select --</option>
                            @foreach ($ramSizes as $ram)
                                <option value="{{ $ram->id }}"
                                    {{ old('ram_size_id', $laptop->ram_size_id) == $ram->id ? 'selected' : '' }}>
                                    {{ $ram->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">RAM type</label>
                        <select name="ram_type" {{ $disabled }} class="form-input">
                            <option value="">-- Select --</option>
                            @foreach ($ramTypes as $type)
                                <option value="{{ $type }}"
                                    {{ old('ram_type', $laptop->ram_type) == $type ? 'selected' : '' }}>
                                    {{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label !mb-0">Screen size</label>
                            <button type="button" onclick="openLookupModal('ScreenSize')"
                                class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ New</button>
                        </div>
                        <select name="screen_size_id" id="ScreenSize_select" {{ $disabled }} class="form-input">
                            <option value="">-- Select --</option>
                            @foreach ($screenSizes as $screen)
                                <option value="{{ $screen->id }}"
                                    {{ old('screen_size_id', $laptop->screen_size_id) == $screen->id ? 'selected' : '' }}>
                                    {{ $screen->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label !mb-0">Storage capacity</label>
                            <button type="button" onclick="openLookupModal('StorageSize')"
                                class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ New</button>
                        </div>
                        <select name="storage_size_id" id="StorageSize_select" {{ $disabled }}
                            class="form-input">
                            <option value="">-- Select --</option>
                            @foreach ($storageSizes as $storage)
                                <option value="{{ $storage->id }}"
                                    {{ old('storage_size_id', $laptop->storage_size_id) == $storage->id ? 'selected' : '' }}>
                                    {{ $storage->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Storage type</label>
                        <select name="storage_type" {{ $disabled }} class="form-input">
                            <option value="">-- Select --</option>
                            @foreach ($storageTypes as $type)
                                <option value="{{ $type }}"
                                    {{ old('storage_type', $laptop->storage_type) == $type ? 'selected' : '' }}>
                                    {{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="lookupModal"
                    class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm">
                    <div class="bg-white rounded-xl p-6 shadow-xl w-80">
                        <h3 class="text-sm font-bold text-slate-900 uppercase mb-4">Add <span id="lookupTitle"></span>
                        </h3>
                        <input type="hidden" id="lookupCategory">
                        <input type="text" id="lookupValue" class="form-input mb-4" placeholder="Enter value...">
                        <div class="flex space-x-2">
                            <button type="button" onclick="closeLookupModal()"
                                class="btn btn-secondary flex-1">Cancel</button>
                            <button type="button" onclick="submitLookup()"
                                class="btn btn-primary flex-1">Save</button>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Status and media
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div>
                            <label class="form-label">Purchase date *</label>
                            <input type="date" name="purchase_date" max="{{ now()->format('Y-m-d') }}"
                                value="{{ old('purchase_date', $laptop->purchase_date ? $laptop->purchase_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                required {{ $disabled }} class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Notes</label>
                            <textarea name="laptop_identifier_notes" rows="3" {{ $disabled }} class="form-input">{{ old('laptop_identifier_notes', $laptop->laptop_identifier_notes) }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Laptop photos (Max 2MB each)</label>
                        <input type="file" name="photos[]" accept=".jpg,.jpeg,.png" multiple {{ $disabled }}
                            class="form-file-input !bg-blue-50 !text-blue-700 hover:!bg-blue-100 cursor-pointer w-full text-sm">
                        <p class="text-xs text-slate-500 mt-1">You can select multiple files at once.</p>

                        @if ($laptop->photos && $laptop->photos->count() > 0)
                            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                @foreach ($laptop->photos as $photo)
                                    <div class="relative group rounded-lg overflow-hidden border border-slate-200">
                                        <img src="{{ Storage::url($photo->photo_path) }}" alt="Laptop Photo"
                                            class="h-24 w-full object-cover">

                                        @if (!$laptop->is_disposed)
                                            <button type="button" onclick="deletePhoto({{ $photo->id }})"
                                                class="absolute top-1 right-1 bg-white/90 text-rose-600 rounded p-1 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-rose-50 shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <script>
                    function deletePhoto(id) {
                        if (confirm('Are you sure you want to delete this photo?')) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/laptops/photos/${id}`;
                            form.innerHTML =
                                `<input type="hidden" name="_method" value="DELETE">
                                                      <input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                </script>
            </div>
    </div>
    @if (!$laptop->is_disposed)
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
            <a href="{{ route('laptops.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary px-5 py-2">Save laptop</button>
        </div>
    @endif
    </form>
    </div>

    @if (!$laptop->is_disposed)
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
            <a href="{{ route('laptops.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary px-5 py-2">Save laptop</button>
        </div>
    @endif
    </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faInput = document.getElementById('fa_code_input');
            const suggestionContainer = document.getElementById('fa_suggestions');

            if (faInput && !faInput.value) {
                fetch("{{ route('laptops.fa-suggestions') }}")
                    .then(res => res.json())
                    .then(suggestions => {
                        if (suggestions.length > 0) {
                            faInput.value = suggestions[0];
                            suggestions.forEach((code) => {
                                const badge = document.createElement('button');
                                badge.type = 'button';
                                badge.className =
                                    "text-[10px] px-2 py-0.5 rounded-full border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors font-mono";
                                badge.innerText = `Suggest: ${code}`;
                                badge.onclick = () => faInput.value = code;
                                suggestionContainer.appendChild(badge);
                            });
                        }
                    });
            }
        });

        const brandDropdown = document.getElementById('Brand_select');
        if (brandDropdown) {
            brandDropdown.addEventListener('change', function() {
                const brandId = this.value;
                const modelSelect = document.getElementById('Model_select');
                modelSelect.innerHTML = '<option value="">Loading...</option>';

                if (!brandId) {
                    modelSelect.innerHTML = '<option value="">-- Select brand first --</option>';
                    return;
                }

                fetch(`/api/lookups/models/${brandId}`)
                    .then(response => response.json())
                    .then(data => {
                        modelSelect.innerHTML = '<option value="">-- Select model --</option>';
                        data.forEach(model => modelSelect.add(new Option(model.value, model.id)));
                    })
                    .catch(() => modelSelect.innerHTML = '<option value="">Error loading models</option>');
            });
        }

        function openLookupModal(category) {
            document.getElementById('lookupCategory').value = category;
            document.getElementById('lookupTitle').innerText = category.replace(/([A-Z])/g, ' $1').trim();
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
            let parentId = null;

            if (!value) return;

            if (category === 'Model') {
                parentId = document.getElementById('Brand_select').value;
                if (!parentId) {
                    alert('Please select a Brand first before adding a new Model.');
                    return;
                }
            }

            try {
                const response = await fetch("{{ route('lookups.quick-store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        category: category,
                        value: value,
                        parent_id: parentId
                    })
                });

                const data = await response.json();
                if (response.ok) {
                    const select = document.getElementById(category + '_select');
                    if (select) {
                        select.add(new Option(data.value, data.id, true, true));
                        select.dispatchEvent(new Event('change'));
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
    </script>
</x-layout>
