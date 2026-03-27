<x-split-layout>
    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md animate-pulse">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Selamat Datang</h1>
        <p class="text-gray-500 mt-3 text-lg">{{ auth()->user()->name }} — Silakan pilih area kerja Anda.</p>
    </div>

    <div class="space-y-6">
        @php
            $allowed = auth()->user()->allowed_division;
            $canPercetakan = $allowed === 'all' || $allowed === 'percetakan';
            $canKonfeksi = $allowed === 'all' || $allowed === 'konfeksi';
        @endphp

        <!-- Divisi Percetakan -->
        <div class="relative group">
            @if(!$canPercetakan)
                <div onclick="showAccessDenied('Percetakan')" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-gray-50/80 backdrop-blur-sm rounded-2xl cursor-pointer border-2 border-dashed border-gray-300">
                    <div class="bg-red-500 text-white p-3 rounded-full shadow-lg mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm font-black text-red-600 uppercase tracking-widest">Anda Tidak Memiliki Akses</span>
                </div>
            @endif
            
            <form method="POST" action="{{ route('division.set') }}">
                @csrf
                <input type="hidden" name="division" value="percetakan">
                <button type="submit" @disabled(!$canPercetakan) class="w-full text-left p-6 border-2 {{ $canPercetakan ? 'border-primary-100 hover:border-primary-600 hover:bg-primary-50 hover:shadow-xl' : 'border-gray-100 opacity-40' }} rounded-2xl transition-all duration-500 flex items-center">
                    <div class="p-4 rounded-xl {{ $canPercetakan ? 'bg-primary-100 text-primary-600 group-hover:bg-primary-600 group-hover:text-white' : 'bg-gray-100 text-gray-400' }} transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-xl font-bold text-gray-900">Percetakan</h3>
                        <p class="text-sm text-gray-500">Advertising, Cetak Undangan, Spanduk</p>
                    </div>
                </button>
            </form>
        </div>

        <!-- Divisi Konfeksi -->
        <div class="relative group">
            @if(!$canKonfeksi)
                <div onclick="showAccessDenied('Konfeksi')" class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-gray-50/80 backdrop-blur-sm rounded-2xl cursor-pointer border-2 border-dashed border-gray-300">
                    <div class="bg-red-500 text-white p-3 rounded-full shadow-lg mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm font-black text-red-600 uppercase tracking-widest">Anda Tidak Memiliki Akses</span>
                </div>
            @endif

            <form method="POST" action="{{ route('division.set') }}">
                @csrf
                <input type="hidden" name="division" value="konfeksi">
                <button type="submit" @disabled(!$canKonfeksi) class="w-full text-left p-6 border-2 {{ $canKonfeksi ? 'border-orange-100 hover:border-orange-600 hover:bg-orange-50 hover:shadow-xl' : 'border-gray-100 opacity-40' }} rounded-2xl transition-all duration-500 flex items-center">
                    <div class="p-4 rounded-xl {{ $canKonfeksi ? 'bg-orange-100 text-orange-600 group-hover:bg-orange-600 group-hover:text-white' : 'bg-gray-100 text-gray-400' }} transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-xl font-bold text-gray-900">Konfeksi</h3>
                        <p class="text-sm text-gray-500">Sewing, Seragam, Jahit Pakaian</p>
                    </div>
                </button>
            </form>
        </div>
    </div>

    <!-- Alert Message Container (Hidden by default) -->
    <div id="access-alert" class="fixed top-10 right-10 z-50 transform translate-x-full transition-transform duration-500">
        <div class="bg-red-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4">
            <div class="bg-red-700 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="font-bold">Akses Ditolak!</p>
                <p class="text-sm opacity-90" id="alert-message">Anda tidak memiliki izin untuk mengakses area ini.</p>
            </div>
        </div>
    </div>

    <script>
        function showAccessDenied(divisionName) {
            const alert = document.getElementById('access-alert');
            const msg = document.getElementById('alert-message');
            msg.innerText = `Maaf, akun Anda tidak memiliki izin untuk masuk ke divisi ${divisionName}.`;
            
            alert.classList.remove('translate-x-full');
            
            setTimeout(() => {
                alert.classList.add('translate-x-full');
            }, 4000);
        }
    </script>

    <div class="mt-8 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-400 hover:text-gray-600 transition-colors flex items-center justify-center mx-auto gap-2 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                </svg>
                Logout Account
            </button>
        </form>
    </div>
</x-split-layout>
