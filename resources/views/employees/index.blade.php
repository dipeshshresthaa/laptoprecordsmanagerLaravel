<x-layout> <div class="max-w-7xl mx-auto p-6 bg-white shadow-sm rounded-lg mt-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Employee Directory</h2>
            
            <div class="flex space-x-4 items-center">
                <form action="{{ route('employees.index') }}" method="GET" class="flex items-center space-x-2">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="show_left_employees" value="1" 
                               onchange="this.form.submit()" 
                               {{ $showLeftEmployees ? 'checked' : '' }} 
                               class="form-checkbox h-5 w-5 text-blue-600 rounded">
                        <span class="text-gray-700 font-medium">Show Inactive (Left)</span>
                    </label>
                </form>
                
                <a href="{{ route('employees.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                    + Add Employee
                </a>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Emp Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($employees as $emp)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $emp->emp_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $emp->fullName }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $emp->role }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2 flex justify-end">
                                <a href="{{ route('employees.edit', $emp) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                
                                <form action="{{ route('employees.destroy', $emp) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout>