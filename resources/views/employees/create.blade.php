<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Karyawan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                                <input type="text" name="name" class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                                @error('name')
                                    <p class="text-red-500 text-xs italic mt-1 feedback-error" id="error-name">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Divisi</label>
                                <select name="division" class="w-full border rounded px-3 py-2 @error('division') border-red-500 @enderror">
                                    <option value="percetakan" {{ old('division') == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                                    <option value="konfeksi" disabled>Konveksi (Belum Tersedia)</option>
                                </select>
                                @error('division')
                                    <p class="text-red-500 text-xs italic mt-1 feedback-error" id="error-division">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jabatan / Role</label>
                                <input type="text" name="role" placeholder="e.g. Staff, Penjahit, Admin" class="w-full border rounded px-3 py-2 @error('role') border-red-500 @enderror" value="{{ old('role') }}">
                                @error('role')
                                    <p class="text-red-500 text-xs italic mt-1 feedback-error" id="error-role">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Gaji Pokok (Base Salary)</label>
                                <input type="number" name="salary_base" class="w-full border rounded px-3 py-2 @error('salary_base') border-red-500 @enderror" value="{{ old('salary_base') }}">
                                @error('salary_base')
                                    <p class="text-red-500 text-xs italic mt-1 feedback-error" id="error-salary_base">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Upah Lembur / Jam</label>
                                <input type="number" name="overtime_rate" class="w-full border rounded px-3 py-2 @error('overtime_rate') border-red-500 @enderror" placeholder="e.g. 15000" value="{{ old('overtime_rate', 0) }}">
                                @error('overtime_rate')
                                    <p class="text-red-500 text-xs italic mt-1 feedback-error" id="error-overtime_rate">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
