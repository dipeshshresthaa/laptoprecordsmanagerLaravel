<x-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-sm rounded-lg mt-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Add New Employee</h2>

        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Employee Code *</label>
                    <input type="text" name="emp_code" value="{{ old('emp_code') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('emp_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="roleSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="Other" {{ old('role') == 'Other' ? 'selected' : '' }}>Other</option>
                        <option value="Partner" {{ old('role') == 'Partner' ? 'selected' : '' }}>Partner</option>
                        <option value="ArticleTrainee" {{ old('role') == 'ArticleTrainee' ? 'selected' : '' }}>Article Trainee</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div id="traineeSection" class="md:col-span-2 bg-blue-50 p-4 rounded-md border border-blue-100" style="display: none;">
                    <h4 class="font-medium text-blue-800 mb-4">Trainee Specific Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Assigned Principal *</label>
                            <select name="principal_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select a Principal</option>
                                @foreach($potentialPrincipals as $principal)
                                    <option value="{{ $principal->id }}" {{ old('principal_id') == $principal->id ? 'selected' : '' }}>
                                        {{ $principal->fullName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('principal_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Articleship Deed (PDF)</label>
                            <input type="file" name="articleship_deed_pdf" accept=".pdf" class="mt-1 block w-full text-sm text-gray-500">
                            @error('articleship_deed_pdf') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('employees.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 font-medium">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm font-medium">Save Employee</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roleSelect');
            const traineeSection = document.getElementById('traineeSection');

            function toggleTraineeSection() {
                if (roleSelect.value === 'ArticleTrainee') {
                    traineeSection.style.display = 'block';
                } else {
                    traineeSection.style.display = 'none';
                }
            }

            roleSelect.addEventListener('change', toggleTraineeSection);
            toggleTraineeSection(); // Run on load to maintain state if validation fails
        });
    </script>
</x-layout>