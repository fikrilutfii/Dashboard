<header class="bg-white/80 backdrop-blur-xl shadow-premium h-20 flex items-center justify-between px-8 sticky top-0 z-20 border-b border-white/60 transition-all duration-300">
    <div class="flex items-center gap-6">
        <!-- Sidebar Toggles -->
        <button @click="sidebarOpen = !sidebarOpen" class="text-zinc-400 hover:text-primary-600 focus:outline-none lg:hidden p-3 rounded-2xl hover:bg-primary-50 transition-all duration-300 active:scale-95">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <button @click="sidebarExpanded = !sidebarExpanded" class="hidden lg:flex text-zinc-400 hover:text-primary-600 focus:outline-none p-3 rounded-2xl hover:bg-primary-50 transition-all duration-300 active:scale-95">
             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <!-- Breadcrumb / Title -->
        <div class="flex items-center gap-3 text-sm">
            <span class="text-[10px] font-black text-zinc-300 uppercase tracking-[0.2em] hidden sm:inline">Portal</span>
            <span class="text-zinc-200 hidden sm:inline">/</span>
            <h2 class="text-lg font-black text-zinc-900 tracking-tight">
                {{ $header ?? 'Dashboard' }}
            </h2>
        </div>
    </div>

    <!-- Right Section -->
    <div class="flex items-center gap-6">
        <!-- Notifications -->
        <button class="p-3 text-zinc-400 hover:text-primary-600 rounded-2xl hover:bg-primary-50 transition-all duration-300 relative group">
             <span class="absolute top-3 right-3 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white animate-pulse"></span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 group-hover:rotate-12 transition-transform">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
        </button>

        <!-- User Profile -->
        <div class="relative pl-6 border-l border-zinc-100" x-data="{ dropdownOpen: false }">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-4 focus:outline-none group">
                <div class="text-right hidden sm:block">
                    <div class="text-sm font-black text-zinc-900 group-hover:text-primary-600 transition-colors leading-none mb-1">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] font-bold text-zinc-400 uppercase tracking-wider">{{ session('division', 'Staff') }}</div>
                </div>
                <!-- Avatar -->
                <div class="w-11 h-11 rounded-2xl premium-gradient flex items-center justify-center text-white font-black text-lg shadow-lg shadow-primary-500/20 group-hover:scale-105 group-hover:rotate-3 transition-all duration-300 ring-4 ring-primary-50 group-hover:ring-primary-100">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
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
                 class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl border border-zinc-100 py-1 z-50 overflow-hidden"
                 style="display: none;">
                
                <div class="bg-zinc-50 px-4 py-3 border-b border-zinc-100 sm:hidden">
                    <p class="text-sm font-semibold text-zinc-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-zinc-500">{{ Auth::user()->email }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-zinc-600 hover:bg-zinc-50 hover:text-indigo-600 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="block px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    </div>
</header>
