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
                                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Divisi</label>
                                <select name="division" class="w-full border rounded px-3 py-2">
                                    <option value="konfeksi">Konfeksi</option>
                                    <option value="percetakan">Percetakan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jabatan / Role</label>
                                <input type="text" name="role" placeholder="e.g. Staff, Penjahit, Admin" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Gaji Pokok (Base Salary)</label>
                                <input type="number" name="salary_base" class="w-full border rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Upah Lembur / Jam</label>
                                <input type="number" name="overtime_rate" class="w-full border rounded px-3 py-2" placeholder="e.g. 15000" value="0">
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
