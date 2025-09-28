{{-- resources/views/partials/grade_component_table.blade.php --}}

<div class="period-container border border-gray-200/80 bg-white rounded-xl shadow-sm overflow-hidden" data-period="{{ $period }}">
    {{-- Accordion Header --}}
    <button type="button" class="accordion-toggle w-full flex justify-between items-center p-4 bg-white hover:bg-gray-50 transition-colors duration-200 cursor-pointer">
        <div class="flex items-center gap-4">
            <span class="font-semibold text-lg text-gray-700 capitalize">{{ $period }}</span>
            <div class="flex items-center">
                <input type="number" value="{{ $weight }}" class="semestral-input w-20 text-center font-bold border-gray-300 rounded-md shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500" data-part="{{ $period }}">
                <span class="ml-2 text-lg text-gray-600">%</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">Sub-total: <span class="sub-total font-bold text-gray-700">100%</span></span>
            <svg class="w-6 h-6 text-gray-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
    </button>
    
    {{-- Accordion Content --}}
    <div class="accordion-content bg-gray-50/50 border-t border-gray-200/80">
        <div class="p-4">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-200">
                    <tr>
                        <th class="p-2 text-left font-semibold text-gray-600">Component</th>
                        <th class="p-2 text-center font-semibold text-gray-600 w-28">Weight (%)</th>
                        <th class="p-2 text-center font-semibold text-gray-600 w-28">Actions</th>
                    </tr>
                </thead>
                <tbody class="component-tbody">
                    {{-- Dynamic rows will be inserted here by JavaScript --}}
                </tbody>
            </table>
            <div class="mt-4 flex justify-end">
                <button type="button" class="add-component-btn inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors py-2 px-3 rounded-lg hover:bg-indigo-50">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Add Main Component
                </button>
            </div>
        </div>
    </div>
</div>