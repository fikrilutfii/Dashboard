@props(['title', 'icon', 'url', 'color' => 'indigo', 'desc' => ''])

<a href="{{ $url }}" 
   class="group relative flex items-center p-4 bg-white rounded-2xl border border-zinc-100 shadow-sm hover:shadow-md hover:border-{{ $color }}-200 transition-all duration-300"
>
    <!-- Icon Container -->
    <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-xl bg-{{ $color }}-50 text-{{ $color }}-600 group-hover:scale-110 group-hover:bg-{{ $color }}-100 transition-transform duration-300">
        {!! $icon !!}
    </div>

    <!-- Text Content -->
    <div class="ml-4">
        <h4 class="text-sm font-semibold text-zinc-800 group-hover:text-{{ $color }}-700 transition-colors">
            {{ $title }}
        </h4>
        @if($desc)
            <p class="text-xs text-zinc-400 mt-0.5 group-hover:text-zinc-500">{{ $desc }}</p>
        @endif
    </div>

    <!-- Subtle Arrow -->
    <div class="absolute right-4 text-zinc-300 group-hover:text-{{ $color }}-400 group-hover:translate-x-1 transition-all duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
            <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
        </svg>
    </div>
</a>
