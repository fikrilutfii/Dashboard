<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Supplier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('suppliers.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded shadow hover:shadow-md transition-all">
                            + Tambah Supplier
                        </a>
                    </div>
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Telepon</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alamat</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                                <tr>
                                    <td class="px-5 py-3 border-b">{{ $supplier->name }}</td>
                                    <td class="px-5 py-3 border-b">{{ $supplier->phone }}</td>
                                    <td class="px-5 py-3 border-b">{{ $supplier->address }}</td>
                                    <td class="px-5 py-3 border-b text-right">
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="text-primary-600 hover:text-primary-900 mr-2">Edit</a>
                                        <!-- Delete form if needed -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
