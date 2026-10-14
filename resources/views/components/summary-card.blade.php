@props(['title', 'value', 'color' => 'indigo', 'icon' => null, 'trend' => null, 'href' => null])

@php
    $colorMaps = [
        'sky' => [
            'icon_bg' => 'bg-cyan-100/80 text-cyan-700',
            'accent'  => 'bg-cyan-400/10',
            'link'    => 'text-cyan-700',
        ],
        'rose' => [
            'icon_bg' => 'bg-pink-100/80 text-pink-700',
            'accent'  => 'bg-pink-400/10',
            'link'    => 'text-pink-700',
        ],
        'amber' => [
            'icon_bg' => 'bg-amber-100/80 text-amber-700',
            'accent'  => 'bg-amber-400/10',
            'link'    => 'text-amber-700',
        ],
        'indigo' => [
            'icon_bg' => 'bg-indigo-100/80 text-indigo-700',
            'accent'  => 'bg-indigo-400/10',
            'link'    => 'text-indigo-700',
        ],
        'emerald' => [
            'icon_bg' => 'bg-emerald-100/80 text-emerald-700',
            'accent'  => 'bg-emerald-400/10',
            'link'    => 'text-emerald-700',
        ],
        'teal' => [
            'icon_bg' => 'bg-teal-100/80 text-teal-800',
            'accent'  => 'bg-teal-400/10',
            'link'    => 'text-teal-800',
        ],
    ];
    $c = $colorMaps[$color] ?? $colorMaps['teal'];
@endphp

@if($href)
<a href="{{ $href }}" class="block relative overflow-hidden rounded-[26px] p-6 glass-card glass-card-hover group cursor-pointer" title="Klik untuk melihat {{ $title }}">
@else
<div class="relative overflow-hidden rounded-[26px] p-6 glass-card glass-card-hover group">
@endif
    <!-- Soft Ambient Accent Glow -->
    <div class="absolute -right-6 -top-6 w-28 h-28 {{ $c['accent'] }} rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
    
    <div class="relative z-10 flex flex-col h-full justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                @if($icon)
                    <div class="w-12 h-12 {{ $c['icon_bg'] }} rounded-2xl flex items-center justify-center shadow-xs group-hover:scale-105 transition-all duration-300">
                        {!! $icon !!}
                    </div>
                @endif

                @if($trend)
                    <div class="flex items-center text-[10px] font-extrabold {{ $trend >= 0 ? 'text-emerald-700 bg-emerald-50/90' : 'text-rose-700 bg-rose-50/90' }} px-2.5 py-1 rounded-full border border-current/10">
                        {{ $trend >= 0 ? '↑' : '↓' }} {{ abs($trend) }}%
                    </div>
                @endif
            </div>

            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-2.5">{{ $title }}</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight leading-none">{{ $value }}</h3>
            </div>
        </div>

        @if($href)
        <div class="mt-5 pt-3 border-t border-slate-100/70 flex items-center justify-between {{ $c['link'] }} text-xs font-bold group-hover:translate-x-0.5 transition-transform duration-300">
            <span>Buka Rincian</span>
            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
        </div>
        @endif
    </div>
@if($href)
</a>
@else
</div>
@endif
