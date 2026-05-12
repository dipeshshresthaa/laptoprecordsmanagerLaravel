<x-layout>
    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 mt-4">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Bulk document uploader</h2>
            <p class="mt-1 text-sm text-slate-500">Upload multiple PDFs at once. The system will automatically attach
                them to the correct employee profiles based on the filename.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden p-8">
            <form action="{{ route('admin.documents.upload.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div
                    class="border-2 border-dashed border-slate-300 rounded-xl p-12 text-center hover:bg-slate-50 transition-colors bg-slate-50/50">
                    <svg class="mx-auto h-12 w-12 text-rose-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>

                    <div class="flex text-sm text-slate-600 justify-center">
                        <label for="file-upload"
                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none px-1">
                            <span>Select multiple PDFs</span>
                            <input id="file-upload" name="documents[]" type="file" accept=".pdf" multiple
                                class="sr-only" required
                                onchange="document.getElementById('file-count').textContent = this.files.length + ' file(s) selected'">
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">PDF files only (Max 10MB each)</p>
                    <p id="file-count" class="mt-4 text-sm font-bold text-slate-900"></p>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-slate-900 hover:bg-slate-800 transition-colors">
                        Process documents
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 bg-amber-50 border-l-4 border-amber-400 p-5 rounded-r-lg shadow-sm">
            <h3 class="text-sm font-bold text-amber-800 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Strict naming convention required
            </h3>
            <div class="text-sm text-amber-700 mt-2 space-y-3">
                <p>The system links PDFs strictly based on the <code>emp_code</code> found in the filename. Files that
                    do not follow this rule will be rejected.</p>

                <div class="bg-amber-100/50 p-3 rounded mt-2 border border-amber-200">
                    <p class="font-bold mb-1">Valid formats:</p>
                    <ul class="list-none ml-2 mt-1 space-y-2 font-mono text-xs">
                        <li>✅ <span class="text-slate-900">[emp_code]_Deed.pdf</span> <br><span
                                class="font-sans text-amber-600 italic">Example: EMP-001_Deed.pdf</span></li>
                        <li>✅ <span class="text-slate-900">[emp_code]_Completion.pdf</span> <br><span
                                class="font-sans text-amber-600 italic">Example: EMP-001_Completion.pdf</span></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</x-layout>
