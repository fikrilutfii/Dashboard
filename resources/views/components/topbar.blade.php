<header class="glass-header h-20 flex items-center justify-between px-6 sm:px-8 sticky top-0 z-20 transition-all duration-300">
    <div class="flex items-center gap-4 sm:gap-6">
        <!-- Sidebar Toggles -->
        <button @click="sidebarOpen = !sidebarOpen" class="glass-pill text-teal-900/70 hover:text-teal-950 focus:outline-none lg:hidden p-2.5 rounded-2xl transition-all duration-300 active:scale-95 shadow-2xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <button @click="sidebarExpanded = !sidebarExpanded" class="hidden lg:flex glass-pill text-teal-900/70 hover:text-teal-950 focus:outline-none p-2.5 rounded-2xl transition-all duration-300 active:scale-95 shadow-2xs">
             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <!-- Welcome Subtitle & Title -->
        <div class="flex flex-col">
            <span class="text-[11px] font-bold text-teal-700/80 tracking-wide hidden sm:block">
                Selamat datang kembali, {{ Auth::user()->name }}
            </span>
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-tight leading-tight">
                {{ $header ?? 'Dashboard' }}
            </h2>
        </div>
    </div>

    <!-- Right Section -->
    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Quick Search Pill -->
        <div class="hidden md:flex items-center gap-2 glass-pill px-3.5 py-1.5 rounded-full text-slate-400 text-xs">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-teal-700/60">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            <span class="text-slate-500 font-medium">Pencarian sistem...</span>
        </div>

        <!-- Notifications -->
        <button class="p-2.5 glass-pill text-teal-900/70 hover:text-teal-950 rounded-2xl transition-all duration-300 relative group active:scale-95 shadow-2xs">
             <span class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full animate-pulse"></span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 group-hover:rotate-12 transition-transform text-slate-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
        </button>

        <!-- User Profile Pill -->
        <div class="relative" x-data="{ dropdownOpen: false }">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2.5 glass-pill px-2.5 py-1.5 rounded-full focus:outline-none group hover:bg-white transition-all shadow-2xs">
                <!-- Avatar -->
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-700 to-emerald-800 flex items-center justify-center text-white font-black text-xs shadow-sm shadow-teal-900/20 group-hover:scale-105 transition-all">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="text-left hidden sm:block pr-1">
                    <div class="text-xs font-bold text-slate-800 leading-none">{{ Auth::user()->name }}</div>
                    <div class="text-[9px] font-bold text-teal-700/80 uppercase tracking-wider mt-0.5">{{ session('division', 'Staff') }}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 text-slate-400 group-hover:text-slate-700 transition-colors">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="dropdownOpen" 
                 @click.away="dropdownOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                 class="absolute right-0 mt-3 w-52 glass-card rounded-2xl shadow-xl py-1.5 z-50 overflow-hidden"
                 style="display: none;">
                
                <div class="bg-white/60 px-4 py-3 border-b border-white/60">
                    <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-white/80 hover:text-teal-800 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Profil Akun
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="block px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50/70 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-rose-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Keluar (Log Out)
                    </a>
                </form>
            </div>
        </div>
    </div>
</header>
