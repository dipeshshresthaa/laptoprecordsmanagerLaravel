<x-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 mt-4">

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">System configuration</h2>
            <p class="mt-1 text-sm text-slate-500">Manage the dropdown options and variables available throughout the
                application.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <div class="lg:col-span-1 sticky top-24">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <h3 id="formTitle" class="font-semibold text-slate-800">Add new entry</h3>
                        </div>
                        <button type="button" id="cancelEditBtn" onclick="cancelEdit()"
                            class="hidden text-xs text-slate-500 hover:text-slate-700 font-medium bg-slate-200 hover:bg-slate-300 px-2 py-1 rounded transition-colors">Cancel
                            Edit</button>
                    </div>

                    <form id="lookupForm" action="{{ route('lookups.store') }}" method="POST" class="p-5 space-y-5">
                        @csrf
                        <div id="methodContainer"></div> <input type="hidden" id="storeRoute"
                            value="{{ route('lookups.store') }}">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>

                            <select name="category" id="categorySelect" required
                                class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">-- Select category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">
                                        {{ ucfirst(strtolower(Str::headline($cat))) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="parentBrandContainer" class="hidden">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Brand</label>
                            <select name="parent_id"
                                class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">-- Select Brand for this Model --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->value }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-500 mt-1.5 flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Models must be linked to a Brand.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Value</label>
                            <input type="text" name="value" required placeholder="e.g. Dell, 16GB, NVMe"
                                class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <div class="pt-2">
                            <button id="submitBtn" type="submit"
                                class="w-full py-2.5 px-4 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 text-sm font-medium transition-colors">
                                Save to database
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($categories as $category)
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col max-h-80">
                        <div
                            class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center shrink-0">
                            <h3 class="font-semibold text-slate-800 tracking-tight">
                                {{ ucfirst(Str::plural(strtolower(Str::headline($category)))) }}
                            </h3>
                            <span class="text-[11px] font-bold bg-slate-200 text-slate-600 px-2.5 py-1 rounded-full">
                                {{ isset($lookups[$category]) ? $lookups[$category]->count() : 0 }} items
                            </span>
                        </div>

                        <div class="overflow-y-auto flex-1 p-1">
                            <ul class="divide-y divide-slate-50">
                                @if (isset($lookups[$category]) && $lookups[$category]->count() > 0)
                                    @foreach ($lookups[$category] as $item)
                                        <li
                                            class="px-3 py-2.5 flex justify-between items-center hover:bg-slate-50 rounded-md transition-colors group">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-sm font-medium text-slate-800">{{ $item->value }}</span>
                                                @if ($category === 'Model' && $item->parent)
                                                    <span class="text-[11px] text-slate-500 flex items-center mt-0.5">
                                                        <svg class="w-3 h-3 mr-1 text-slate-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                                                            </path>
                                                        </svg>
                                                        {{ $item->parent->value }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div
                                                class="flex items-center space-x-1 shrink-0 ml-2 opacity-0 group-hover:opacity-100 transition-all">

                                                <button type="button" data-id="{{ $item->id }}"
                                                    data-value="{{ $item->value }}"
                                                    data-category="{{ $item->category }}"
                                                    data-parent="{{ $item->parent_id ?? '' }}"
                                                    onclick="editLookup(this)"
                                                    class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition-all outline-none"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <form action="{{ route('lookups.destroy', $item) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete {{ addslashes($item->value) }}?');"
                                                    class="m-0 p-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded transition-all outline-none"
                                                        title="Delete">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <li
                                        class="p-6 text-sm text-slate-400 text-center flex flex-col items-center justify-center">
                                        <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                        No entries yet.
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <script>
        // Shows/Hides Parent Brand selection
        document.getElementById('categorySelect').addEventListener('change', function() {
            const brandContainer = document.getElementById('parentBrandContainer');
            if (this.value === 'Model') {
                brandContainer.classList.remove('hidden');
                brandContainer.querySelector('select').setAttribute('required', 'required');
            } else {
                brandContainer.classList.add('hidden');
                brandContainer.querySelector('select').removeAttribute('required');
            }
        });

        // Inline Editor Logic
        // Inline Editor Logic
        // Inline Editor Logic
        function editLookup(buttonElement) {
            // Read variables safely from the button's HTML data attributes
            const id = buttonElement.getAttribute('data-id');
            const value = buttonElement.getAttribute('data-value');
            const category = buttonElement.getAttribute('data-category');
            const parentId = buttonElement.getAttribute('data-parent');

            const form = document.getElementById('lookupForm');
            const title = document.getElementById('formTitle');
            const btn = document.getElementById('submitBtn');
            const methodContainer = document.getElementById('methodContainer');
            const cancelBtn = document.getElementById('cancelEditBtn');
            
            // Clean the base URL
            let baseUrl = document.getElementById('storeRoute').value;
            baseUrl = baseUrl.replace(/\/$/, ""); 
            
            // Populate Fields
            document.querySelector('select[name="category"]').value = category;
            document.querySelector('input[name="value"]').value = value;
            document.getElementById('categorySelect').dispatchEvent(new Event('change'));
            
            if (category === 'Model' && parentId) {
                document.querySelector('select[name="parent_id"]').value = parentId;
            }

            // Transform UI to "Edit Mode"
            title.textContent = 'Edit Entry';
            btn.textContent = 'Update Database';
            btn.classList.replace('bg-blue-600', 'bg-amber-500');
            btn.classList.replace('hover:bg-blue-700', 'hover:bg-amber-600');
            cancelBtn.classList.remove('hidden');

            // CHANGE ROUTE TO UPDATE (PUT)
            form.action = baseUrl + '/' + id;
            methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            // Scroll up to form
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Cancel Edit Logic
        function cancelEdit() {
            const form = document.getElementById('lookupForm');
            const title = document.getElementById('formTitle');
            const btn = document.getElementById('submitBtn');
            const methodContainer = document.getElementById('methodContainer');
            const cancelBtn = document.getElementById('cancelEditBtn');

            // Reset UI
            form.reset();
            document.getElementById('categorySelect').dispatchEvent(new Event('change'));
            title.textContent = 'Add New Entry';
            btn.textContent = 'Save to Database';
            btn.classList.replace('bg-amber-500', 'bg-blue-600');
            btn.classList.replace('hover:bg-amber-600', 'hover:bg-blue-700');
            cancelBtn.classList.add('hidden');

            // Reset Form Action back to default POST
            form.action = document.getElementById('storeRoute').value;
            methodContainer.innerHTML = '';
        }
    </script>
</x-layout>
