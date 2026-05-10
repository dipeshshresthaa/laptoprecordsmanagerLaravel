<x-layout>
    <div class="max-w-4xl mx-auto p-4 sm:p-6 mt-4">
        
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">
                    {{ $laptop->exists ? 'Edit Laptop Details' : 'Add New Laptop' }}
                </h2>
                @if($laptop->exists && $laptop->creator)
                    <p class="text-xs text-slate-500 mt-1">Created by {{ $laptop->creator->username }} on {{ $laptop->created_at->format('M d, Y') }}</p>
                @endif
            </div>
            <a href="{{ route('laptops.index') }}" class="text-sm font-medium text-blue-600">&larr; Back</a>
        </div>

        @if($laptop->is_disposed)
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6 text-sm text-amber-800">
                ⚠ This record is READ-ONLY because the laptop has been marked as Disposed.
            </div>
        @endif

        <form action="{{ $laptop->exists ? route('laptops.update', $laptop) : route('laptops.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            @csrf
            @if($laptop->exists) @method('PUT') @endif
            
            @php $disabled = $laptop->is_disposed ? 'disabled' : ''; @endphp

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Serial number *</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $laptop->serial_number) }}" required {{ $disabled }} class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">FA code</label>
                    <input type="text" name="laptop_fa_code" value="{{ old('laptop_fa_code', $laptop->laptop_fa_code) }}" {{ $disabled }} class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Brand *</label>
                    <select name="brand_id" id="brandSelect" required {{ $disabled }} class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm">
                        <option value="">-- Select brand --</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $laptop->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Model *</label>
                    <select name="model_id" id="modelSelect" required {{ $disabled }} class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm">
                        <option value="">-- Select brand first --</option>
                        @foreach($models as $model)
                            <option value="{{ $model->id }}" {{ old('model_id', $laptop->model_id) == $model->id ? 'selected' : '' }}>{{ $model->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Processor</label>
                    <select name="processor_id" {{ $disabled }} class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm">
                        <option value="">-- Select --</option>
                        @foreach($processors as $proc)
                            <option value="{{ $proc->id }}" {{ old('processor_id', $laptop->processor_id) == $proc->id ? 'selected' : '' }}>{{ $proc->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <select name="status" required {{ $disabled }} class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ old('status', $laptop->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Purchase date *</label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date', $laptop->purchase_date ? $laptop->purchase_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required {{ $disabled }} class="mt-1 w-full rounded-lg border-slate-300 shadow-sm text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Laptop photo (Max 2MB)</label>
                    <input type="file" name="photo" accept=".jpg,.jpeg,.png" {{ $disabled }} class="mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @if($laptop->laptop_photo_path)
                        <p class="text-xs text-emerald-600 mt-1 mt-1">Current image uploaded.</p>
                    @endif
                </div>
            </div>

            @if(!$laptop->is_disposed)
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
                    <a href="{{ route('laptops.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 text-sm">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 text-sm font-medium">Save laptop</button>
                </div>
            @endif
        </form>
    </div>

    <script>
        document.getElementById('brandSelect').addEventListener('change', function() {
            const brandId = this.value;
            const modelSelect = document.getElementById('modelSelect');
            
            // Clear current options
            modelSelect.innerHTML = '<option value="">Loading...</option>';
            
            if(!brandId) {
                modelSelect.innerHTML = '<option value="">-- Select brand first --</option>';
                return;
            }

            // Fetch new models
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