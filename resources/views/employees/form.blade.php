<x-layout>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 mt-4 sm:mt-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                    {{ $employee->exists ? 'Edit employee: ' . $employee->first_name : 'Add new employee' }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $employee->exists ? 'Update the details for this employee.' : 'Fill out the form below to create a new employee record.' }}
                </p>
            </div>
            <a href="{{ route('employees.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                &larr; Back to directory
            </a>
        </div>

        <form action="{{ $employee->exists ? route('employees.update', $employee) : route('employees.store') }}"
            method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            @if ($employee->exists)
                @method('PUT')
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-800">Employment details</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Employee code *</label>
                        <input type="text" name="emp_code" value="{{ old('emp_code', $employee->emp_code) }}"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('emp_code')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Role *</label>
                        <select name="role" id="roleSelect"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="Other" {{ old('role', $employee->role) == 'Other' ? 'selected' : '' }}>Other
                            </option>
                            <option value="Partner" {{ old('role', $employee->role) == 'Partner' ? 'selected' : '' }}>
                                Partner</option>
                            <option value="ArticleTrainee"
                                {{ old('role', $employee->role) == 'ArticleTrainee' ? 'selected' : '' }}>Article trainee
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Designation</label>
                        <input type="text" name="designation"
                            value="{{ old('designation', $employee->designation) }}"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Joining date</label>
                            <input type="date" name="joining_date"
                                value="{{ old('joining_date', $employee->joining_date?->format('Y-m-d')) }}"
                                max="9999-12-31"
                                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-slate-700">
                        </div>
                    </div>
                </div>

                <div id="traineeSection" class="p-6 bg-blue-50 border-t border-blue-100 hidden">
                    <h4 class="font-semibold text-blue-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"></path>
                        </svg>
                        Trainee specific details
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Assigned principal *</label>
                            <select name="principal_id"
                                class="w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Select a principal</option>
                                @foreach ($potentialPrincipals as $principal)
                                    <option value="{{ $principal->id }}"
                                        {{ old('principal_id', $employee->principal_id) == $principal->id ? 'selected' : '' }}>
                                        {{ $principal->fullName ?? $principal->first_name . ' ' . $principal->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('principal_id')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Articleship deed (PDF)</label>

                            @if ($employee->articleship_deed_path)
                                <div
                                    class="mb-3 flex items-center p-2.5 bg-emerald-50 rounded-lg border border-emerald-100">
                                    <svg class="w-4 h-4 text-emerald-600 mr-2 shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-xs text-emerald-800 font-medium mr-auto">Current document
                                        saved</span>
                                    <a href="{{ Storage::url($employee->articleship_deed_path) }}" target="_blank"
                                        class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                        View &rarr;
                                    </a>
                                </div>
                            @endif

                            <input type="file" name="articleship_deed" accept=".pdf"
                                class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-colors border border-slate-300 rounded-lg p-1">

                            <p class="text-[10px] text-slate-500 mt-1">
                                {{ $employee->exists ? 'Upload to replace existing deed (Max 10MB)' : 'Attach PDF deed (Optional, Max 10MB)' }}
                            </p>

                            @error('articleship_deed')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-800">Personal information</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">First name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('first_name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Middle name</label>
                        <input type="text" name="middle_name"
                            value="{{ old('middle_name', $employee->middle_name) }}"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Last name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('last_name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Phone number</label>
                        <input type="text" name="phone_number"
                            value="{{ old('phone_number', $employee->phone_number) }}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">PAN number</label>
                        <input type="text" name="pan_number"
                            value="{{ old('pan_number', $employee->pan_number) }}" maxlength="9" pattern="\d{9}"
                            title="PAN number must be exactly 9 digits"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('pan_number')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">CIT number</label>
                        <input type="text" name="cit_number"
                            value="{{ old('cit_number', $employee->cit_number) }}"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm uppercase">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-800">Address details</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">State / Province</label>
                        <input type="text" name="address_state"
                            value="{{ old('address_state', $employee->address_state) }}"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">District</label>
                        <input type="text" name="address_district"
                            value="{{ old('address_district', $employee->address_district) }}"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Municipality</label>
                        <input type="text" name="address_municipality"
                            value="{{ old('address_municipality', $employee->address_municipality) }}"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-800">Banking details</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Bank name</label>
                        <input type="text" name="bank_name" list="bank-names"
                            value="{{ old('bank_name', $employee->bank_name) }}" autocomplete="off"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">

                        <datalist id="bank-names">
                            @foreach ($bankNames as $bank)
                                <option value="{{ $bank }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Branch</label>
                        <input type="text" name="bank_branch" list="bank-branches"
                            value="{{ old('bank_branch', $employee->bank_branch) }}" autocomplete="off"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">

                        <datalist id="bank-branches">
                            @foreach ($bankBranches as $branch)
                                <option value="{{ $branch }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Account number</label>
                        <input type="text" name="bank_account_number"
                            value="{{ old('bank_account_number', $employee->bank_account_number) }}"
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm font-mono">
                        @error('bank_account_number')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('employees.index') }}"
                    class="px-5 py-2.5 border border-slate-300 rounded-lg text-slate-700 bg-white hover:bg-slate-50 font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm font-medium transition-all">
                    {{ $employee->exists ? 'Update employee' : 'Save employee record' }}
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roleSelect');
            const traineeSection = document.getElementById('traineeSection');

            function toggleTraineeSection() {
                if (roleSelect.value === 'ArticleTrainee') {
                    traineeSection.classList.remove('hidden');
                } else {
                    traineeSection.classList.add('hidden');
                }
            }

            roleSelect.addEventListener('change', toggleTraineeSection);
            toggleTraineeSection(); // Run on load to maintain state if validation fails
        });
    </script>
</x-layout>
