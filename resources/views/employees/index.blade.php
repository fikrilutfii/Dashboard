<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <!-- Action Buttons -->
                    <div class="mb-4 flex flex-wrap gap-2 justify-end">
                        <a href="{{ route('employees.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded shadow hover:bg-primary-700 transition">
                            + Karyawan Baru
                        </a>
                        <a href="{{ route('payrolls.index') }}" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 transition">
                            Lihat Penggajian
                        </a>
                    </div>

                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Divisi</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Jabatan</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase">Gaji Pokok</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase">Lembur/Jam</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <td class="px-5 py-3 border-b">
                                        <div class="font-bold">{{ $employee->name }}</div>
                                    </td>
                                    <td class="px-5 py-3 border-b uppercase text-xs">{{ $employee->division }}</td>
                                    <td class="px-5 py-3 border-b">{{ $employee->role }}</td>
                                    <td class="px-5 py-3 border-b text-right font-mono text-sm">Rp {{ number_format($employee->salary_base, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 border-b text-right font-mono text-xs text-zinc-500">Rp {{ number_format($employee->overtime_rate, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 border-b text-right flex justify-end gap-2">
                                        <a href="{{ route('kasbons.create', ['employee_id' => $employee->id]) }}" class="text-orange-600 hover:text-orange-900 text-xs border border-orange-600 px-2 py-1 rounded">Kasbon</a>
                                        <a href="{{ route('employees.edit', $employee) }}" class="text-blue-600 hover:text-blue-900 text-xs border border-blue-600 px-2 py-1 rounded">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
