@props(['header' => null])

@php
    $headerRaw = isset($header) ? (string)$header : '';
    $hasRichHeader = $headerRaw !== '' && (strlen($headerRaw) !== strlen(strip_tags($headerRaw)));
    $headerText = trim(strip_tags($headerRaw));
@endphp

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
            @if(!$hasRichHeader && $headerText !== '' && $headerText !== 'Dashboard')
                <h2 class="text-base sm:text-lg font-extrabold text-slate-800 tracking-tight leading-tight">
                    {{ $headerText }}
                </h2>
            @else
                <div class="flex items-center gap-2">
                    <span class="text-xs sm:text-sm font-extrabold text-slate-800 tracking-tight">
                        @if(session('division') === 'percetakan')
                            Divisi Percetakan & Offset
                        @elseif(session('division') === 'konfeksi')
                            Divisi Konveksi
                        @else
                            Sistem Mini ERP
                        @endif
                    </span>
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
            @endif
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

        <!-- Notifications Dropdown -->
        <div class="relative" x-data="{ notificationOpen: false, notifTab: 'all' }">
            <button @click="notificationOpen = !notificationOpen" 
                    class="p-2.5 glass-pill text-teal-900/70 hover:text-teal-950 rounded-2xl transition-all duration-300 relative group active:scale-95 shadow-2xs focus:outline-none"
                    title="Pemberitahuan Sistem">
                @if(($systemNotifications['urgent_count'] ?? 0) > 0)
                    <span class="absolute -top-1 -right-1 px-1.5 py-0.5 text-[9px] font-black bg-rose-500 text-white rounded-full shadow-xs animate-pulse">
                        {{ $systemNotifications['urgent_count'] }}
                    </span>
                @elseif(($systemNotifications['total_count'] ?? 0) > 0)
                    <span class="absolute top-2 right-2 w-2 h-2 bg-amber-400 rounded-full"></span>
                @endif
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 group-hover:rotate-12 transition-transform text-slate-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </button>

            <!-- Notification Dropdown Panel (Frosted Glassmorphic) -->
            <div x-show="notificationOpen" 
                 @click.away="notificationOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                 class="absolute right-0 mt-3 w-80 sm:w-96 glass-card rounded-[26px] shadow-2xl z-50 overflow-hidden border border-white/90"
                 style="display: none;">
                
                <!-- Header -->
                <div class="p-4 bg-white/70 border-b border-slate-100/80 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-teal-50 text-teal-800 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-slate-800">Pemberitahuan Sistem</h4>
                            <span class="text-[10px] text-slate-400 font-semibold">{{ $systemNotifications['total_count'] ?? 0 }} notifikasi terpantau</span>
                        </div>
                    </div>
                    @if(($systemNotifications['urgent_count'] ?? 0) > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                            {{ $systemNotifications['urgent_count'] }} Perlu Tindakan
                        </span>
                    @endif
                </div>

                <!-- Tabs Filter -->
                <div class="flex items-center gap-1 p-2 bg-slate-50/50 border-b border-slate-100/80 text-[11px] font-bold">
                    <button @click="notifTab = 'all'" :class="notifTab === 'all' ? 'bg-white text-teal-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800'" class="px-3 py-1 rounded-xl transition-all">
                        Semua ({{ $systemNotifications['total_count'] ?? 0 }})
                    </button>
                    <button @click="notifTab = 'debt'" :class="notifTab === 'debt' ? 'bg-white text-teal-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800'" class="px-3 py-1 rounded-xl transition-all">
                        Hutang Perusahaan
                    </button>
                    <button @click="notifTab = 'invoice'" :class="notifTab === 'invoice' ? 'bg-white text-teal-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800'" class="px-3 py-1 rounded-xl transition-all">
                        Faktur Klien
                    </button>
                </div>

                <!-- Notifications List -->
                <div class="max-h-80 overflow-y-auto divide-y divide-slate-100/80">
                    @forelse(($systemNotifications['items'] ?? []) as $notif)
                        <a href="{{ $notif['url'] }}" 
                           x-show="notifTab === 'all' || notifTab === '{{ $notif['category'] }}'"
                           class="p-3.5 block hover:bg-teal-50/30 transition-colors group">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5
                                    @if($notif['badge_color'] === 'rose') bg-rose-50 text-rose-600 border border-rose-100
                                    @elseif($notif['badge_color'] === 'amber') bg-amber-50 text-amber-700 border border-amber-100
                                    @elseif($notif['badge_color'] === 'teal') bg-teal-50 text-teal-700 border border-teal-100
                                    @else bg-blue-50 text-blue-600 border border-blue-100
                                    @endif">
                                    @if($notif['icon'] === 'currency')
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @elseif($notif['icon'] === 'box')
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                                    @elseif($notif['icon'] === 'invoice')
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <span class="text-xs font-bold text-slate-800 truncate group-hover:text-teal-900 transition-colors">{{ $notif['title'] }}</span>
                                        <span class="text-[9px] font-extrabold px-2 py-0.5 rounded-full flex-shrink-0
                                            @if($notif['badge_color'] === 'rose') bg-rose-50 text-rose-700 border border-rose-200
                                            @elseif($notif['badge_color'] === 'amber') bg-amber-50 text-amber-700 border border-amber-200
                                            @elseif($notif['badge_color'] === 'teal') bg-teal-50 text-teal-700 border border-teal-200
                                            @else bg-blue-50 text-blue-700 border border-blue-200
                                            @endif">
                                            {{ $notif['badge'] }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 leading-snug line-clamp-2">{{ $notif['message'] }}</p>
                                    <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1.5 font-medium">
                                        <span>{{ $notif['category_label'] }}</span>
                                        <span>{{ $notif['time'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center text-slate-400">
                            <div class="w-10 h-10 mx-auto rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center mb-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="text-xs font-bold text-slate-700">Semua Terpantau Aman</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Tidak ada tagihan mendesak atau stok habis saat ini.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Footer -->
                <div class="p-3 bg-white/70 border-t border-slate-100/80 text-center flex items-center justify-between text-xs">
                    <a href="{{ route('activity-logs.index') }}" class="text-[11px] font-bold text-teal-800 hover:text-teal-950 hover:underline">
                        Riwayat Log Aktivitas
                    </a>
                    <a href="{{ route('company-debts.index') }}" class="text-[11px] font-bold text-slate-500 hover:text-slate-800">
                        Lihat Semua Tagihan →
                    </a>
                </div>
            </div>
        </div>

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
