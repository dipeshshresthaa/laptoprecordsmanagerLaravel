<x-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 mt-4">

        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Assign laptop</h2>
                <p class="text-sm text-slate-500 mt-1">Review device history and specifications before assigning.</p>
            </div>
            <a href="{{ route('laptops.index') }}"
                class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">&larr; Back to
                inventory</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <div class="lg:col-span-1 space-y-6">
                <div class="card">
                    <div class="h-48 bg-slate-50 border-b border-slate-100 flex items-center justify-center p-4">
                        <div class="w-full md:w-1/3 flex flex-col items-center">
                            @if ($laptop->photos && $laptop->photos->count() > 0)

                                <div class="relative w-full aspect-square overflow-hidden rounded-lg border border-slate-200 group bg-slate-50 mb-3"
                                    id="main-image-container">
                                    @php $firstPhotoUrl = Storage::url($laptop->photos->first()->photo_path); @endphp
                                    <img src="{{ $firstPhotoUrl }}" alt="Laptop Main View" id="main-gallery-image"
                                        class="w-full h-full object-contain transition-transform duration-500 ease-in-out group-hover:scale-[1.7] origin-center cursor-crosshair"
                                        onmousemove="zoomImage(event, this)" onmouseleave="resetZoom(this)">
                                </div>

                                @if ($laptop->photos->count() > 1)
                                    <div class="flex gap-2 w-full overflow-x-auto pb-2 custom-scrollbar">
                                        @foreach ($laptop->photos as $index => $photo)
                                            @php $photoUrl = Storage::url($photo->photo_path); @endphp
                                            <button type="button" onclick="swapImage('{{ $photoUrl }}', this)"
                                                class="thumbnail-btn flex-shrink-0 w-16 h-16 rounded border-2 overflow-hidden transition-all {{ $index === 0 ? 'border-blue-500 shadow-md' : 'border-transparent hover:border-slate-300 opacity-70 hover:opacity-100' }}">
                                                <img src="{{ $photoUrl }}" alt="Thumbnail {{ $index + 1 }}"
                                                    class="w-full h-full object-cover">
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div
                                    class="w-full aspect-square bg-slate-100 rounded-lg flex flex-col items-center justify-center text-slate-400 border border-slate-200">
                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor"
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
                        <div class="space-y-2 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Laptop code:</span>
                                <span class="font-medium text-slate-900">{{ $laptop->laptop_fa_code ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Serial number:</span>
                                <span class="font-medium text-slate-900">{{ $laptop->serial_number }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Service tag:</span>
                                <span class="font-medium text-slate-900">{{ $laptop->service_tag ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <h4
                            class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">
                            Hardware Specs</h4>
                        <div class="space-y-3 mb-6">
                            <div>
                                <p class="text-[11px] text-slate-500">Processor</p>
                                <p class="text-sm font-medium text-slate-900">{{ $laptop->processor->value ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[11px] text-slate-500">RAM</p>
                                    <p class="text-sm font-medium text-slate-900">
                                        {{ $laptop->ramSize->value ?? 'N/A' }}
                                        <span class="text-xs text-slate-400 font-normal">{{ $laptop->ram_type }}</span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[11px] text-slate-500">Storage</p>
                                    <p class="text-sm font-medium text-slate-900">
                                        {{ $laptop->storageSize->value ?? 'N/A' }} <span
                                            class="text-xs text-slate-400 font-normal">{{ $laptop->storage_type }}</span>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-500">Screen size</p>
                                <p class="text-sm font-medium text-slate-900">{{ $laptop->screenSize->value ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <h4
                            class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-slate-100">
                            Current Status</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[11px] text-slate-500 mb-1">Status</p>
                                <span
                                    class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800">{{ $laptop->status }}</span>
                            </div>
                            <div>
                                <p class="text-[11px] text-slate-500">Purchased on</p>
                                <p class="text-sm font-medium text-slate-900">
                                    {{ $laptop->purchase_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Assign to employee</h3>
                    </div>

                    <form action="{{ route('laptops.store_assign', $laptop) }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-6">
                            <div>
                                <label class="form-label">Select employee *</label>
                                <select name="employee_id" required class="form-input">
                                    <option value="">-- Choose an active employee --</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->full_name }}
                                            ({{ $emp->emp_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label">Assignment date *</label>
                                <input type="date" name="assigned_date" min="{{ $minDate->format('Y-m-d') }}"
                                    max="{{ now()->format('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                                    class="form-input">
                                <p class="text-xs text-slate-500 mt-1">Cannot be earlier than
                                    {{ $minDate->format('M d, Y') }} (purchase or last return date).</p>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
                            <a href="{{ route('laptops.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5">Confirm assignment</button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header flex justify-between items-center !py-4">
                        <h3 class="card-title">Device history</h3>
                        <span
                            class="text-xs font-medium bg-slate-200 text-slate-600 px-2.5 py-1 rounded-full">{{ $laptop->assignments->count() }}
                            records</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-base">
                            <thead class="table-head">
                                <tr>
                                    <th class="table-th">Employee</th>
                                    <th class="table-th">Assigned</th>
                                    <th class="table-th">Returned</th>
                                    <th class="table-th">Condition</th>
                                    <th class="table-th">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($laptop->assignments()->latest('assigned_date')->get() as $history)
                                    <tr class="table-row">
                                        <td class="table-td text-sm font-medium text-slate-900">
                                            {{ $history->employee->first_name }} {{ $history->employee->last_name }}
                                        </td>
                                        <td class="table-td text-sm text-slate-600">
                                            {{ $history->assigned_date->format('M d, Y') }}
                                        </td>
                                        <td class="table-td text-sm text-slate-600">
                                            @if ($history->returned_date)
                                                {{ $history->returned_date->format('M d, Y') }}
                                            @else
                                                <span class="text-blue-600 font-medium">Current</span>
                                            @endif
                                        </td>
                                        <td class="table-td text-sm text-slate-600">
                                            {{ $history->return_condition ?? '-' }}
                                        </td>
                                        <td class="table-td text-sm text-slate-600 truncate max-w-xs">
                                            {{ $history->return_reason ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-8 text-center text-sm text-slate-500 italic">
                                            No assignment history found for this device.
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
</x-layout>
