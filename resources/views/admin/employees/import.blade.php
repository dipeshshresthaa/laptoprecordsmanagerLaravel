<x-layout>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 mt-4 sm:mt-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Bulk upload employees</h2>
                <p class="mt-1 text-sm text-slate-500">Upload an EXCEL file to add multiple staff members at once.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.documents.upload') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                    <span class="mr-2">📄</span> Go to Bulk PDF Uploader
                </a>

                <a href="{{ route('admin.employees.template') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors shadow-sm">
                    <span class="mr-2">📥</span> Download EXCEL template
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-8">
            <form action="{{ route('admin.employees.import.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div
                    class="border-2 border-dashed border-slate-300 rounded-xl p-12 text-center hover:bg-slate-50 transition-colors bg-slate-50/50">
                    <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <div class="flex text-sm text-slate-600 justify-center">
                        <label for="file-upload"
                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none px-1">
                            <span>Upload an Excel file</span>
                            <input id="file-upload" name="excel_file" type="file" accept=".xlsx, .xls, .csv"
                                class="sr-only" required
                                onchange="document.getElementById('file-name').textContent = this.files[0].name">
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">.XLSX files only up to 5MB</p>
                    <p id="file-name" class="mt-4 text-sm font-bold text-slate-900"></p>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                        Process upload
                    </button>
                </div>
            </form>

            <div class="mt-6 bg-amber-50 border-l-4 border-amber-400 p-5 rounded-r-lg shadow-sm">
                <h3 class="text-sm font-bold text-amber-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Formatting instructions
                </h3>
                <div class="text-sm text-amber-700 mt-2 space-y-2">
                    <p>Please ensure your <strong>EXCEL file (.xlsx)</strong> uses the exact column headers provided in
                        the downloadable template.</p>
                    <ul class="list-disc list-inside ml-2 space-y-1">
                        <li><strong>Role</strong> must be exactly: <code
                                class="bg-amber-100 px-1 rounded">Partner</code>, <code
                                class="bg-amber-100 px-1 rounded">ArticleTrainee</code>, or <code
                                class="bg-amber-100 px-1 rounded">Other</code>.</li>
                        <li><strong>Dates</strong> should be formatted as YYYY-MM-DD.</li>
                        <li><strong>is_active</strong> column should be <strong>1</strong> for Active and
                            <strong>0</strong> for Left/Inactive.
                        </li>
                    </ul>
                    <p class="mt-2 text-xs font-semibold">Note: Please check the "Instructions & rules" tab inside the
                        Excel template for full details on all 21 columns.</p>
                </div>
            </div>
        </div>
</x-layout>
