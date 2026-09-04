<x-app-layout>
    <x-slot name="header">Manajemen Akses Pengguna</x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-900">Atur Akses Akun</h2>
                    <p class="text-sm text-gray-500 mt-1">Ubah role dan divisi akun lain. Perubahan berlaku saat pengguna login kembali.</p>
                </div>

                @if(session('success'))
                    <div class="mx-6 mt-5 p-3 rounded bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-5 py-3 text-left">Nama</th>
                                <th class="px-5 py-3 text-left">Username</th>
                                <th class="px-5 py-3 text-left">Role</th>
                                <th class="px-5 py-3 text-left">Divisi</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($users as $user)
                                <tr>
                                    <td class="px-5 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-5 py-3 text-gray-600">{{ $user->username }}</td>
                                    <td class="px-5 py-3" colspan="3">
                                        @if($user->username === 'tracker')
                                            <span class="text-gray-500">Pemilik akses (tidak dapat diubah)</span>
                                        @else
                                            <form action="{{ route('user-access.update', $user) }}" method="POST" class="flex flex-wrap items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <select name="role" class="border rounded px-3 py-2">
                                                    @foreach($roles as $value => $label)
                                                        <option value="{{ $value }}" @selected($user->role === $value)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                <select name="allowed_division" class="border rounded px-3 py-2">
                                                    <option value="all" @selected($user->allowed_division === 'all')>Semua divisi</option>
                                                    <option value="percetakan" @selected($user->allowed_division === 'percetakan')>Percetakan</option>
                                                    <option value="konfeksi" @selected($user->allowed_division === 'konfeksi')>Konfeksi</option>
                                                    <option value="peternakan" @selected($user->allowed_division === 'peternakan')>Peternakan Ayam</option>
                                                </select>
                                                <button class="bg-indigo-600 text-white rounded px-4 py-2 hover:bg-indigo-700">Simpan</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
