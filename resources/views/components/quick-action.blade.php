@props(['title', 'icon', 'url', 'color' => 'indigo', 'desc' => ''])

<a href="{{ $url }}" 
   class="group relative flex items-center p-6 bg-white/80 backdrop-blur-xl rounded-[1.5rem] border border-white/60 shadow-premium hover:shadow-premium-hover hover:-translate-y-1 transition-all duration-500"
>
    <!-- Icon Container -->
    <div class="flex-shrink-0 w-14 h-14 flex items-center justify-center rounded-2xl bg-{{ $color }}-500 text-white shadow-lg shadow-{{ $color }}-500/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
        {!! $icon !!}
    </div>

    <!-- Text Content -->
    <div class="ml-5">
        <h4 class="text-md font-black text-zinc-900 group-hover:text-primary-600 transition-colors leading-none mb-1">
            {{ $title }}
        </h4>
        @if($desc)
            <p class="text-[11px] font-bold text-zinc-400 uppercase tracking-wide group-hover:text-zinc-500">{{ $desc }}</p>
        @endif
    </div>

    <!-- Subtle Arrow -->
    <div class="absolute right-6 text-zinc-200 group-hover:text-primary-400 group-hover:translate-x-2 transition-all duration-500">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
            <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
    </div>
</a>
