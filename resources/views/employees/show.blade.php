<x-layout>
    <div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8 mt-4">

        <div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <a href="{{ route('employees.index') }}"
                        class="text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors">&larr; Back to
                        Directory</a>
                </div>
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">{{ $employee->first_name }}
                    {{ $employee->last_name }}</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">
                    {{ $employee->emp_code }} &bull; {{ $employee->role }} &bull;
                    {{ $employee->department ?? 'No Department' }}
                </p>
            </div>

            <a href="{{ route('employees.edit', $employee) }}"
                class="inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-bold rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition-colors shrink-0">
                <svg class="mr-2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                    </path>
                </svg>
                Edit Profile
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1 space-y-6">

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6">
                    <h3
                        class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                        Profile Status</h3>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Employment Status</p>
                            @if ($employee->is_active)
                                <span
                                    class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Active
                                </span>
                            @else
                                <span
                                    class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Inactive / Left
                                </span>
                            @endif
                        </div>

                        @if ($employee->principal)
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Reports To (Principal)</p>
                                <a href="{{ route('employees.show', $employee->principal) }}"
                                    class="mt-1 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors flex items-center">
                                    {{ $employee->principal->first_name }} {{ $employee->principal->last_name }}
                                </a>
                            </div>
                        @endif

                        @if ($employee->trainees->count() > 0)
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Direct Reports</p>
                                <p class="mt-1 text-sm font-bold text-slate-900">{{ $employee->trainees->count() }}
                                    Active Trainees</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-6">
                    <h3
                        class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                        Documents</h3>

                    <div class="space-y-3 mb-6">
                        @if ($employee->articleship_deed_path)
                            <a href="{{ Storage::url($employee->articleship_deed_path) }}" target="_blank"
                                class="flex items-center justify-between p-3 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-colors group">
                                <div class="flex items-center text-sm font-medium text-slate-700">
                                    <span class="text-rose-500 mr-2">📄</span> Articleship Deed
                                </div>
                                <span class="text-xs font-bold text-blue-600 group-hover:text-blue-800">View
                                    &rarr;</span>
                            </a>
                        @else
                            <div
                                class="flex items-center p-3 rounded-lg border border-dashed border-slate-300 bg-slate-50 text-sm text-slate-500">
                                <span class="mr-2 opacity-50">📄</span> No Deed on file
                            </div>
                        @endif

                        @if ($employee->articleship_completion_path)
                            <a href="{{ Storage::url($employee->articleship_completion_path) }}" target="_blank"
                                class="flex items-center justify-between p-3 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-colors group">
                                <div class="flex items-center text-sm font-medium text-slate-700">
                                    <span class="text-rose-500 mr-2">📄</span> Completion Cert.
                                </div>
                                <span class="text-xs font-bold text-blue-600 group-hover:text-blue-800">View
                                    &rarr;</span>
                            </a>
                        @else
                            <div
                                class="flex items-center p-3 rounded-lg border border-dashed border-slate-300 bg-slate-50 text-sm text-slate-500">
                                <span class="mr-2 opacity-50">📄</span> No Completion Cert.
                            </div>
                        @endif
                    </div>

                    @if (Auth::check() && Auth::user()->is_admin)
                        <form action="{{ route('employees.documents.upload', $employee) }}" method="POST"
                            enctype="multipart/form-data" class="border-t border-slate-100 pt-4">
                            @csrf
                            <p class="text-xs font-bold text-slate-900 mb-3">Upload / Replace PDFs</p>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Articleship
                                        Deed</label>
                                    <input type="file" name="articleship_deed" accept=".pdf"
                                        class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors border border-slate-200 rounded-md p-1 cursor-pointer">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Completion
                                        Certificate</label>
                                    <input type="file" name="articleship_completion" accept=".pdf"
                                        class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors border border-slate-200 rounded-md p-1 cursor-pointer">
                                </div>
                            </div>

                            <button type="submit"
                                class="mt-5 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                Upload Documents
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-900">Hardware Assignment History</h3>
                        <p class="text-xs text-slate-500 mt-1">A complete audit log of all IT assets assigned to this
                            user.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-white">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Laptop</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Date Assigned</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($assignments as $assignment)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('laptops.show', $assignment->laptop) }}"
                                                class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                                {{ $assignment->laptop->serial_number }}
                                            </a>
                                            <div class="text-xs text-slate-500 mt-0.5">
                                                {{ $assignment->laptop->brand->value ?? 'Unknown Brand' }}
                                                {{ $assignment->laptop->model->value ?? '' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                            {{ \Carbon\Carbon::parse($assignment->assigned_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($assignment->returned_date)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800">
                                                    Returned on
                                                    {{ \Carbon\Carbon::parse($assignment->returned_date)->format('M d, Y') }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                                    Currently Holding
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <p class="mt-4 text-sm font-medium text-slate-900">No hardware history</p>
                                            <p class="mt-1 text-sm text-slate-500">This employee has never been assigned
                                                a laptop.</p>
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
