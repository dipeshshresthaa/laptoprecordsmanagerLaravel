<x-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 mt-4 sm:mt-8">
        
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Laptop inventory</h2>
                <p class="mt-1 text-sm text-slate-500">Manage entity hardware, specifications, and assignments.</p>
            </div>
            
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 items-start sm:items-center">
                
                <form action="{{ route('laptops.index') }}" method="GET" class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search SN or FA Code..." 
                               class="block w-full sm:w-64 pl-10 pr-3 py-2 border border-slate-300 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors">
                    </div>

                    <div class="flex items-center bg-white border border-slate-200 rounded-lg px-3 py-2 shadow-sm shrink-0">
                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input type="checkbox" name="show_disposed" value="1" onchange="this.form.submit()" {{ $showDisposed ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-700">Show disposed</span>
                        </label>
                    </div>
                    <button type="submit" class="hidden"></button>
                </form>
                
                <a href="{{ route('laptops.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-all shrink-0">
                    <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add laptop
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Hardware ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Make & model</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Purchase date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($laptops as $laptop)
                            <tr class="hover:bg-slate-50 transition-colors group {{ $laptop->is_disposed ? 'bg-slate-50/50 opacity-75' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-slate-900">{{ $laptop->serial_number }}</div>
                                    <div class="text-xs text-slate-500">{{ $laptop->laptop_fa_code ?? 'No FA Code' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900">{{ $laptop->brand->value ?? 'Unknown' }}</div>
                                    <div class="text-xs text-slate-500">{{ $laptop->model->value ?? 'Unknown' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                    {{ $laptop->purchase_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($laptop->status === 'Available')
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Available</span>
                                    @elseif($laptop->status === 'Assigned')
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Assigned</span>
                                    @elseif($laptop->status === 'In Repair')
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">In Repair</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Disposed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('laptops.edit', $laptop) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors opacity-0 group-hover:opacity-100">
                                        {{ $laptop->is_disposed ? 'View' : 'Edit' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">No laptops found</h3>
                                    <p class="mt-1 text-sm text-slate-500">Get started by adding hardware to your inventory.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>