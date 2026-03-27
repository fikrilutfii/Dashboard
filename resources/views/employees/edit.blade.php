<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ $employee->name }}" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Divisi</label>
                                <select name="division" class="w-full border rounded px-3 py-2">
                                    <option value="konfeksi" {{ $employee->division == 'konfeksi' ? 'selected' : '' }}>Konfeksi</option>
                                    <option value="percetakan" {{ $employee->division == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jabatan / Role</label>
                                <input type="text" name="role" value="{{ $employee->role }}" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Gaji Pokok (Base Salary)</label>
                                <input type="number" name="salary_base" value="{{ $employee->salary_base }}" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Upah Lembur / Jam</label>
                                <input type="number" name="overtime_rate" value="{{ $employee->overtime_rate }}" class="w-full border rounded px-3 py-2">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
