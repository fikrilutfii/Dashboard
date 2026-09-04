@props(['title', 'value', 'color' => 'indigo', 'icon' => null, 'trend' => null, 'href' => null])

@if($href)
<a href="{{ $href }}" class="block relative overflow-hidden rounded-[2rem] p-7 border border-white/60 shadow-premium hover:shadow-premium-hover hover:-translate-y-2 transition-all duration-500 group bg-white/80 backdrop-blur-xl cursor-pointer" title="Klik untuk melihat {{ $title }}">
@else
<div class="relative overflow-hidden rounded-[2rem] p-7 border border-white/60 shadow-premium hover:shadow-premium-hover hover:-translate-y-2 transition-all duration-500 group bg-white/80 backdrop-blur-xl">
@endif
    <!-- Accent Background -->
    <div class="absolute -right-8 -top-8 w-32 h-32 bg-{{ $color }}-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
    
    <div class="relative z-10 flex flex-col h-full justify-between">
        <div>
            <div class="flex items-center justify-between mb-5">
                @if($icon)
                    <div class="p-4 bg-{{ $color }}-500 rounded-2xl text-white shadow-lg shadow-{{ $color }}-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 cursor-pointer">
                        {!! $icon !!}
                    </div>
                @endif

                @if($trend)
                    <div class="flex items-center text-[11px] font-bold {{ $trend >= 0 ? 'text-emerald-600 bg-emerald-50' : 'text-rose-600 bg-rose-50' }} px-2.5 py-1 rounded-full border border-current/10">
                        {{ $trend >= 0 ? '↑' : '↓' }} {{ abs($trend) }}%
                    </div>
                @endif
            </div>

            <div>
                <p class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest leading-none mb-2">{{ $title }}</p>
                <h3 class="text-2xl font-black text-zinc-900 tracking-tight leading-none">{{ $value }}</h3>
            </div>
        </div>

        @if($href)
        <div class="mt-5 flex items-center gap-1 text-{{ $color }}-600 text-xs font-bold group-hover:translate-x-1 transition-transform duration-300">
            <span>Lihat Detail Fitur</span>
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
        </div>
        @endif
    </div>
@if($href)
</a>
@else
</div>
@endif
