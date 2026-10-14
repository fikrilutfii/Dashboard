@props(['title', 'icon', 'url', 'color' => 'indigo', 'desc' => ''])

@php
    $colorMaps = [
        'indigo' => 'bg-indigo-100/90 text-indigo-700',
        'violet' => 'bg-purple-100/90 text-purple-700',
        'teal'   => 'bg-teal-100/90 text-teal-800',
        'rose'   => 'bg-rose-100/90 text-rose-700',
        'amber'  => 'bg-amber-100/90 text-amber-700',
        'emerald'=> 'bg-emerald-100/90 text-emerald-700',
        'sky'    => 'bg-cyan-100/90 text-cyan-700',
    ];
    $iconClass = $colorMaps[$color] ?? 'bg-teal-100/90 text-teal-800';
@endphp

<a href="{{ $url }}" 
   class="group relative flex items-center p-5 glass-card glass-card-hover rounded-[22px] shadow-xs"
>
    <!-- Icon Container -->
    <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-2xl {{ $iconClass }} shadow-2xs group-hover:scale-105 transition-all duration-300">
        {!! $icon !!}
    </div>

    <!-- Text Content -->
    <div class="ml-4 pr-6">
        <h4 class="text-sm font-extrabold text-slate-800 group-hover:text-teal-900 transition-colors leading-tight mb-1">
            {{ $title }}
        </h4>
        @if($desc)
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-slate-500">{{ $desc }}</p>
        @endif
    </div>

    <!-- Subtle Arrow -->
    <div class="absolute right-5 text-slate-300 group-hover:text-teal-700 group-hover:translate-x-1 transition-all duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
            <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
    </div>
</a>
