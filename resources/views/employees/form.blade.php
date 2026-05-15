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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Employment details</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="form-label">Employee code *</label>
                        <input type="text" name="emp_code" value="{{ old('emp_code', $employee->emp_code) }}"
                            class="form-input">
                        @error('emp_code')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Role *</label>
                        <select name="role" id="roleSelect" class="form-input">
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
                        <label class="form-label">Designation</label>
                        <input type="text" name="designation"
                            value="{{ old('designation', $employee->designation) }}" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Joining date</label>
                        <input type="date" name="joining_date"
                            value="{{ old('joining_date', $employee->joining_date?->format('Y-m-d')) }}"
                            max="9999-12-31" class="form-input text-slate-700">
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
                            <label class="form-label">Assigned principal *</label>
                            <select name="principal_id" class="form-input border-blue-200">
                                <option value="">Select a principal</option>
                                @foreach ($potentialPrincipals as $principal)
                                    <option value="{{ $principal->id }}"
                                        {{ old('principal_id', $employee->principal_id) == $principal->id ? 'selected' : '' }}>
                                        {{ $principal->fullName ?? $principal->first_name . ' ' . $principal->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('principal_id')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Articleship deed (PDF)</label>
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
                                        class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">View
                                        &rarr;</a>
                                </div>
                            @endif
                            <input type="file" name="articleship_deed" accept=".pdf" class="form-file-input">
                            <p class="text-[10px] text-slate-500 mt-1">
                                {{ $employee->exists ? 'Upload to replace existing deed (Max 10MB)' : 'Attach PDF deed (Optional, Max 10MB)' }}
                            </p>
                            @error('articleship_deed')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Personal information</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="form-label">First name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}"
                            class="form-input">
                        @error('first_name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Middle name</label>
                        <input type="text" name="middle_name"
                            value="{{ old('middle_name', $employee->middle_name) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Last name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}"
                            class="form-input">
                        @error('last_name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Phone number</label>
                        <input type="text" name="phone_number"
                            value="{{ old('phone_number', $employee->phone_number) }}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">PAN number</label>
                        <input type="text" name="pan_number"
                            value="{{ old('pan_number', $employee->pan_number) }}" maxlength="9" pattern="\d{9}"
                            title="PAN number must be exactly 9 digits"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-input">
                        @error('pan_number')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">CIT number</label>
                        <input type="text" name="cit_number"
                            value="{{ old('cit_number', $employee->cit_number) }}" class="form-input uppercase">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Address details</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="form-label">State / Province</label>
                        <input type="text" name="address_state"
                            value="{{ old('address_state', $employee->address_state) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">District</label>
                        <input type="text" name="address_district"
                            value="{{ old('address_district', $employee->address_district) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Municipality</label>
                        <input type="text" name="address_municipality"
                            value="{{ old('address_municipality', $employee->address_municipality) }}"
                            class="form-input">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Banking details</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label !mb-0">Bank name</label>
                            <button type="button" onclick="openLookupModal('BankName')"
                                class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ New</button>
                        </div>
                        <select name="bank_name_id" id="BankName_select" class="form-input">
                            <option value="">-- Select bank --</option>
                            @foreach ($bankNames as $bank)
                                <option value="{{ $bank->id }}"
                                    {{ old('bank_name_id', $employee->bank_name_id) == $bank->id ? 'selected' : '' }}>
                                    {{ $bank->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('bank_name_id')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="form-label !mb-0">Branch</label>
                            <button type="button" onclick="openLookupModal('BankBranch')"
                                class="text-blue-600 hover:text-blue-800 text-xs font-bold">+ New</button>
                        </div>
                        <select name="bank_branch_id" id="BankBranch_select" class="form-input">
                            <option value="">-- Select branch --</option>
                            @foreach ($bankBranches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('bank_branch_id', $employee->bank_branch_id) == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('bank_branch_id')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label">Account number</label>
                        <input type="text" name="bank_account_number"
                            value="{{ old('bank_account_number', $employee->bank_account_number) }}"
                            class="form-input font-mono">
                        @error('bank_account_number')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('employees.index') }}" class="btn btn-secondary px-5 py-2.5">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 py-2.5">
                    {{ $employee->exists ? 'Update employee' : 'Save employee record' }}
                </button>
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
                        <button type="button" onclick="submitLookup()" class="btn btn-primary flex-1">Save</button>
                    </div>
                </div>
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
            toggleTraineeSection();
        });

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

            if (!value) return;

            try {
                const response = await fetch("{{ route('lookups.quick-store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        category: category,
                        value: value
                    })
                });

                const data = await response.json();
                if (response.ok) {
                    const select = document.getElementById(category + '_select');
                    if (select) {
                        // We pass 'data.value' for both label and value so it saves text instead of lookup ID
                        select.add(new Option(data.value, data.id, true, true));
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
