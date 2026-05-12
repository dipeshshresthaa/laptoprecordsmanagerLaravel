<x-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 mt-4">

        <div class="sm:flex sm:items-center sm:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Laptop inventory</h2>
                <p class="mt-1 text-sm text-slate-500">Manage company hardware, specifications, and lifecycles.</p>
            </div>
            
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                
                @if (session('receipt_url'))
                    <a href="{{ session('receipt_url') }}" target="_blank" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-emerald-200 text-sm font-bold rounded-lg shadow-sm text-emerald-800 bg-emerald-50 hover:bg-emerald-100 focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                        <svg class="mr-2 -ml-1 w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Print last receipt
                    </a>
                @endif

                <a href="{{ route('laptops.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add laptop
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-visible">

            <form action="{{ route('laptops.index') }}" method="GET"
                class="p-4 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-t-xl">

                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by Serial Number or FA Code..."
                        class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors shadow-sm">
                </div>

                <div
                    class="flex items-center bg-white border border-slate-300 rounded-lg px-4 py-2 shadow-sm shrink-0 h-[42px]">
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="checkbox" name="show_disposed" value="1" onchange="this.form.submit()"
                            {{ $showDisposed ? 'checked' : '' }} class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600 transition-colors">
                        </div>
                        <span
                            class="ml-3 text-sm font-medium text-slate-700 group-hover:text-slate-900 transition-colors">Include
                            disposed</span>
                    </label>
                </div>
                <button type="submit" class="hidden"></button>
            </form>

            <div class="overflow-x-auto min-h-[300px]">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-white">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Hardware ID</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Make & Model</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Purchase Date</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($laptops as $laptop)
                            <tr
                                class="hover:bg-slate-50/80 transition-colors {{ $laptop->is_disposed ? 'bg-slate-50/50 opacity-60 grayscale-[50%]' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-slate-900 font-mono">{{ $laptop->serial_number }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5">
                                        {{ $laptop->laptop_fa_code ?? 'No FA Code' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $laptop->brand->value ?? 'Unknown' }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $laptop->model->value ?? 'Unknown' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    {{ $laptop->purchase_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($laptop->status === 'Available')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                            Available
                                        </span>
                                    @elseif($laptop->status === 'Assigned')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span> Assigned
                                        </span>
                                    @elseif($laptop->status === 'In repair')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> In repair
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span> Disposed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                    <div class="relative inline-block text-left">
                                        <button type="button" data-target="dropdown-{{ $laptop->id }}"
                                            class="dropdown-trigger inline-flex items-center justify-center px-3 py-1.5 border border-slate-200 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                                            Manage
                                            <svg class="ml-1.5 -mr-1 w-4 h-4 text-slate-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>

                                        <div id="dropdown-{{ $laptop->id }}"
                                            class="dropdown-menu fixed w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-[100] hidden transform opacity-0 scale-95 transition-all duration-150 origin-top-right border border-slate-100">
                                            <div class="py-1.5" role="menu">
                                                <a href="{{ route('laptops.history', $laptop) }}"
                                                    class="flex items-center px-4 py-2 text-sm text-slate-900 hover:bg-slate-100 font-bold bg-slate-50 border-b border-slate-100">
                                                    <span class="mr-2">📄</span> View full history
                                                </a>
                                                @if ($laptop->status === 'Available')
                                                    <a href="{{ route('laptops.assign', $laptop) }}"
                                                        class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium">
                                                        <span class="mr-2">👨‍💻</span> Assign to employee
                                                    </a>
                                                @endif

                                                @if ($laptop->status === 'Assigned')
                                                    <a href="{{ route('laptops.return', $laptop) }}"
                                                        class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-amber-50 hover:text-amber-700 font-medium">
                                                        <span class="mr-2">↩️</span> Process return
                                                    </a>
                                                @endif

                                                @if (!$laptop->is_disposed)
                                                    @if ($laptop->status === 'In repair')
                                                        <a href="{{ route('laptops.repair_return', $laptop) }}"
                                                            class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium">
                                                            <span class="mr-2">📥</span> Receive from repair
                                                        </a>
                                                        <div class="border-t border-slate-100 my-1.5"></div>
                                                    @else
                                                        <a href="{{ route('laptops.repair', $laptop) }}"
                                                            class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-rose-50 hover:text-rose-700">
                                                            <span class="mr-2">🛠️</span> Send to repair
                                                        </a>
                                                    @endif

                                                    <a href="{{ route('laptops.upgrade', $laptop) }}"
                                                        class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                                        <span class="mr-2">⚙️</span> Log upgrade
                                                    </a>
                                                    <div class="border-t border-slate-100 my-1.5"></div>
                                                @endif



                                                <a href="{{ route('laptops.edit', $laptop) }}"
                                                    class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                                    <span class="mr-2">✏️</span> Edit base details
                                                </a>

                                                @if (!$laptop->is_disposed)
                                                    <a href="{{ route('laptops.dispose', $laptop) }}"
                                                        class="flex items-center px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">
                                                        <span class="mr-2">🗑️</span> Mark as disposed
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center bg-slate-50/30">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <h3 class="mt-4 text-sm font-medium text-slate-900">No laptops found</h3>
                                    <p class="mt-1 text-sm text-slate-500">Get started by adding hardware to your
                                        inventory.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('laptops.create') }}"
                                            class="inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                                            Add New Laptop
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let activeDropdown = null;

            // 1. ESCAPE THE CSS TRAP: Move all menus to the root <body> tag
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                document.body.appendChild(menu);
            });

            // 2. Toggle dropdowns on click
            document.querySelectorAll('.dropdown-trigger').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();

                    // Find the specific menu we linked via the ID
                    const targetId = this.getAttribute('data-target');
                    const menu = document.getElementById(targetId);

                    if (activeDropdown && activeDropdown !== menu) {
                        closeMenu(activeDropdown);
                    }

                    if (menu.classList.contains('hidden')) {
                        openMenu(menu, this);
                        activeDropdown = menu;
                    } else {
                        closeMenu(menu);
                        activeDropdown = null;
                    }
                });
            });

            // Close when clicking anywhere else on the page
            document.addEventListener('click', function() {
                if (activeDropdown) {
                    closeMenu(activeDropdown);
                    activeDropdown = null;
                }
            });

            // Close if user scrolls the page to prevent floating orphans
            window.addEventListener('scroll', function() {
                if (activeDropdown) {
                    closeMenu(activeDropdown);
                    activeDropdown = null;
                }
            }, true);

            function openMenu(menu, button) {
                menu.classList.remove('hidden');

                // Calculate exact coordinates based on the button
                const rect = button.getBoundingClientRect();
                const menuHeight = menu.offsetHeight || 230;
                const menuWidth = 192;

                // Anchor to the right edge of the button
                menu.style.left = (rect.right - menuWidth) + 'px';

                // Smart placement: Open upward if bottom of screen is too close
                if (rect.bottom + menuHeight > window.innerHeight) {
                    menu.style.top = (rect.top - menuHeight) + 'px';
                    menu.classList.replace('origin-top-right', 'origin-bottom-right');
                } else {
                    menu.style.top = (rect.bottom + 6) + 'px';
                    menu.classList.replace('origin-bottom-right', 'origin-top-right');
                }

                setTimeout(() => {
                    menu.classList.remove('opacity-0', 'scale-95');
                    menu.classList.add('opacity-100', 'scale-100');
                }, 10);
            }

            function closeMenu(menu) {
                menu.classList.remove('opacity-100', 'scale-100');
                menu.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 150);
            }
        });
    </script>
</x-layout>
