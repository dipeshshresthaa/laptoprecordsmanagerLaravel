<x-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 mt-0">

        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">System settings and lookups</h2>
                <p class="mt-1 text-sm text-slate-500">Manage dropdown options and system-wide configurations.</p>
            </div>
            <button onclick="openModal('Brand')" class="btn btn-primary shrink-0">
                ➕ Add new option
            </button>
        </div>

        <div class="border-b border-slate-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button onclick="switchTab('hardware')" id="tab-hardware"
                    class="tab-btn whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                    <span class="mr-2">💻</span> IT hardware
                </button>
                <button onclick="switchTab('finance')" id="tab-finance"
                    class="tab-btn whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300">
                    <span class="mr-2">🏦</span> HR and finance
                </button>
                <button onclick="switchTab('vendors')" id="tab-vendors"
                    class="tab-btn whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300">
                    <span class="mr-2">🛠️</span> External vendors
                </button>
            </nav>
        </div>

        @php
            // Categorize the lookups for the UI
            $hardwareCategories = ['Brand', 'Model', 'Processor', 'RamSize', 'StorageSize', 'ScreenSize'];
            $financeCategories = ['BankName', 'BankBranch'];
            $vendorCategories = ['Vendor'];
        @endphp

        <div class="card p-6">
            <div id="content-hardware" class="tab-content block">
                <div class="mb-6 pb-4 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">IT hardware specifications</h3>
                        <p class="text-sm text-slate-500">Manage specifications used when registering laptops.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($hardwareCategories as $category)
                        <div>
                            <h4
                                class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center justify-between border-b border-slate-200 pb-2">
                                {{ preg_replace('/([A-Z])/', ' $1', $category) }}
                                <button onclick="openModal('{{ $category }}')"
                                    class="text-blue-600 hover:text-blue-800 text-xs">+ Add</button>
                            </h4>
                            <ul class="space-y-2">
                                @forelse($lookups->get($category, []) as $lookup)
                                    <li
                                        class="text-sm text-slate-600 flex justify-between items-center group bg-slate-50 px-3 py-2 rounded">
                                        <span>{{ $lookup->value }}</span>
                                        <form action="{{ route('lookups.destroy', $lookup) }}" method="POST"
                                            class="inline opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700"
                                                onclick="return confirm('Delete this option?')"><svg class="w-4 h-4"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg></button>
                                        </form>
                                    </li>
                                @empty
                                    <li class="text-sm text-slate-400 italic px-3 py-2">No options added yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="content-finance" class="tab-content hidden">
                <div class="mb-6 pb-4 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">HR and finance lookups</h3>
                        <p class="text-sm text-slate-500">Manage payroll and banking institutions used for employees.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($financeCategories as $category)
                        <div>
                            <h4
                                class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center justify-between border-b border-slate-200 pb-2">
                                {{ preg_replace('/([A-Z])/', ' $1', $category) }}
                                <button onclick="openModal('{{ $category }}')"
                                    class="text-blue-600 hover:text-blue-800 text-xs">+ Add</button>
                            </h4>
                            <ul class="space-y-2">
                                @forelse($lookups->get($category, []) as $lookup)
                                    <li
                                        class="text-sm text-slate-600 flex justify-between items-center group bg-slate-50 px-3 py-2 rounded">
                                        <span>{{ $lookup->value }}</span>
                                        <form action="{{ route('lookups.destroy', $lookup) }}" method="POST"
                                            class="inline opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700"
                                                onclick="return confirm('Delete this option?')"><svg class="w-4 h-4"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg></button>
                                        </form>
                                    </li>
                                @empty
                                    <li class="text-sm text-slate-400 italic px-3 py-2">No options added yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="content-vendors" class="tab-content hidden">
                <div class="mb-6 pb-4 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">External vendors</h3>
                        <p class="text-sm text-slate-500">Manage repair shops and service centers.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($vendorCategories as $category)
                        <div>
                            <h4
                                class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center justify-between border-b border-slate-200 pb-2">
                                Repair centers / vendors
                                <button onclick="openModal('{{ $category }}')"
                                    class="text-blue-600 hover:text-blue-800 text-xs">+ Add</button>
                            </h4>
                            <ul class="space-y-2">
                                @forelse($lookups->get($category, []) as $lookup)
                                    <li
                                        class="text-sm text-slate-600 flex justify-between items-center group bg-slate-50 px-3 py-2 rounded border-l-4 border-slate-300">
                                        <span>{{ $lookup->value }}</span>
                                        <form action="{{ route('lookups.destroy', $lookup) }}" method="POST"
                                            class="inline opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700"
                                                onclick="return confirm('Delete this option?')"><svg class="w-4 h-4"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg></button>
                                        </form>
                                    </li>
                                @empty
                                    <li class="text-sm text-slate-400 italic px-3 py-2">No vendors added yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <div id="lookupModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <form action="{{ route('lookups.store') }}" method="POST"
            class="bg-white rounded-xl p-6 shadow-xl w-full max-w-md">
            @csrf
            <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">Add new option</h3>

            <div class="space-y-4">
                <div>
                    <label class="form-label">Category</label>
                    <select name="category" id="modalCategory" class="form-input" required>
                        <optgroup label="Hardware">
                            @foreach ($hardwareCategories as $cat)
                                <option value="{{ $cat }}">
                                    {{ ucfirst(strtolower(trim(preg_replace('/([A-Z])/', ' $1', $cat)))) }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Finance and HR">
                            @foreach ($financeCategories as $cat)
                                <option value="{{ $cat }}">
                                    {{ ucfirst(strtolower(trim(preg_replace('/([A-Z])/', ' $1', $cat)))) }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="External">
                            @foreach ($vendorCategories as $cat)
                                <option value="{{ $cat }}">Vendor</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div id="parentModelDiv" class="hidden">
                    <label class="form-label">Parent brand (Required for models)</label>
                    <select name="parent_id" class="form-input">
                        <option value="">-- Select brand --</option>
                        @foreach ($lookups->get('Brand', []) as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Value / Name</label>
                    <input type="text" name="value" class="form-input"
                        placeholder="e.g. Nabil Bank, Dell, i7..." required>
                </div>
            </div>

            <div class="mt-6 flex space-x-3 justify-end">
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Save option</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = localStorage.getItem('activeLookupTab') || 'hardware';
            switchTab(activeTab);
        });

        function switchTab(tabName) {
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.remove('block');
                el.classList.add('hidden');
            });

            // Reset all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-slate-500');
            });

            // Show active content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            document.getElementById('content-' + tabName).classList.add('block');

            // Highlight active button
            document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-slate-500');
            document.getElementById('tab-' + tabName).classList.add('border-blue-500', 'text-blue-600');

            // 2. Save the current tab to localStorage
            localStorage.setItem('activeLookupTab', tabName);
        }

        function openModal(category = 'Brand') {
            const select = document.getElementById('modalCategory');
            select.value = category;

            // Show/Hide Parent Brand selector logic
            const parentDiv = document.getElementById('parentModelDiv');
            if (category === 'Model') {
                parentDiv.classList.remove('hidden');
                parentDiv.querySelector('select').required = true;
            } else {
                parentDiv.classList.add('hidden');
                parentDiv.querySelector('select').required = false;
            }

            document.getElementById('lookupModal').classList.remove('hidden');
            document.getElementById('lookupModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('lookupModal').classList.add('hidden');
            document.getElementById('lookupModal').classList.remove('flex');
        }

        // Add event listener to category select to toggle Parent Brand field dynamically
        document.getElementById('modalCategory').addEventListener('change', function() {
            openModal(this.value);
        });
    </script>
</x-layout>
