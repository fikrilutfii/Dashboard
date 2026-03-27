<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate Payroll (Gaji)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('payrolls.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <!-- Employee Selection -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Karyawan</label>
                                <select name="employee_id" id="employee_id" class="w-full border rounded px-3 py-2" onchange="fetchEmployeeData()" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }} - {{ ucfirst($emp->division) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Period -->
                            <div class="flex gap-2">
                                <div class="w-1/2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Periode Mulai</label>
                                    <input type="date" name="period_start" value="{{ date('Y-m-01') }}" class="w-full border rounded px-3 py-2" required>
                                </div>
                                <div class="w-1/2">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Periode Selesai</label>
                                    <input type="date" name="period_end" value="{{ date('Y-m-t') }}" class="w-full border rounded px-3 py-2" required>
                                </div>
                            </div>
                        </div>

                        <!-- Calculation Box -->
                        <div class="p-4 bg-gray-50 border rounded-lg space-y-3">
                            <h3 class="font-bold border-b pb-2">Rincian Gaji</h3>
                            
                            <div class="flex justify-between items-center">
                                <span>Gaji Pokok:</span>
                                <input type="number" id="base_salary" class="bg-gray-100 text-right border-none font-bold" readonly value="0">
                            </div>

                            <div class="flex justify-between items-center text-indigo-600">
                                <div class="flex flex-col">
                                    <span>Lembur (Jam):</span>
                                    <span class="text-[10px] text-indigo-400">Rate: Rp <span id="ot_rate_display">0</span>/jam</span>
                                </div>
                                <input type="number" name="overtime_hours" id="overtime_hours" class="text-right border rounded px-2 py-1 w-32 border-indigo-200" value="0" step="0.5" onchange="calcTotal()">
                                <input type="hidden" id="ot_rate" value="0">
                            </div>

                            <div class="flex justify-between items-center">
                                <span>Bonus / Tambahan:</span>
                                <input type="number" name="bonus" id="bonus" class="text-right border rounded px-2 py-1 w-32" value="0" onchange="calcTotal()">
                            </div>

                            <div class="flex justify-between items-center text-red-600">
                                <div class="flex items-center">
                                    <input type="checkbox" name="deduct_kasbon" id="deduct_kasbon" value="1" class="mr-2" onchange="toggleDeduction()">
                                    <label for="deduct_kasbon">Potong Kasbon (Total Sisa: <span id="total_remaining_display">0</span>)</label>
                                </div>
                                <input type="number" name="deduction_amount" id="deduction_amount" class="text-right border rounded px-2 py-1 w-32 border-red-300 text-red-600 font-bold" value="0" onchange="calcTotal()">
                            </div>

                            <div class="flex justify-between items-center border-t pt-2 mt-2 text-lg font-bold">
                                <span>Total Gaji Diterima:</span>
                                <span id="total_salary_display">Rp 0</span>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-green-600 text-white font-bold py-2 px-6 rounded hover:bg-green-700">
                                Proses & Bayar Gaji
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fetchEmployeeData() {
            const id = document.getElementById('employee_id').value;
            if(!id) return;

            fetch(`/api/employees/${id}/data`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('base_salary').value = data.salary_base;
                    // document.getElementById('kasbon_amount').value = data.open_kasbon;
                    
                    document.getElementById('total_remaining_display').innerText = new Intl.NumberFormat('id-ID').format(data.open_kasbon);
                    
                    // Set recommended deduction
                    const deductionInput = document.getElementById('deduction_amount');
                    deductionInput.value = data.recommended_deduction;
                    deductionInput.dataset.max = data.open_kasbon; // Store max logic if needed
                    
                    // Auto check if there is a deduction
                    document.getElementById('ot_rate_display').innerText = new Intl.NumberFormat('id-ID').format(data.overtime_rate);
                    document.getElementById('ot_rate').value = data.overtime_rate;
                    
                    calcTotal();
                });
        }

        function toggleDeduction() {
            const checkbox = document.getElementById('deduct_kasbon');
            const input = document.getElementById('deduction_amount');
            if (!checkbox.checked) {
                input.value = 0;
            } 
            calcTotal();
        }

        function calcTotal() {
            const base = parseFloat(document.getElementById('base_salary').value) || 0;
            const otHours = parseFloat(document.getElementById('overtime_hours').value) || 0;
            const otRate = parseFloat(document.getElementById('ot_rate').value) || 0;
            const bonus = parseFloat(document.getElementById('bonus').value) || 0;
            const deduct = parseFloat(document.getElementById('deduction_amount').value) || 0;
            
            const otPay = otHours * otRate;
            const total = base + otPay + bonus - deduct;
            
            document.getElementById('total_salary_display').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(total);
        }
    </script>
</x-app-layout>
