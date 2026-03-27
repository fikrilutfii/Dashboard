@props(['title', 'value', 'color' => 'indigo', 'icon' => null, 'trend' => null])

<div class="relative bg-white overflow-hidden rounded-2xl p-6 border border-zinc-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
    <!-- Background Blob (Subtle) -->
    <div class="absolute -right-6 -top-6 w-24 h-24 bg-{{ $color }}-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
    
    <div class="relative z-10 flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">{{ $title }}</p>
            <h3 class="text-2xl font-bold text-zinc-800 mt-2 tracking-tight">{{ $value }}</h3>
            
            @if($trend)
                <div class="mt-2 flex items-center text-xs font-medium {{ $trend >= 0 ? 'text-green-500' : 'text-red-500' }}">
                     <span class="bg-{{ $trend >= 0 ? 'green' : 'red' }}-50 px-1.5 py-0.5 rounded mr-1">
                        {{ $trend >= 0 ? '+' : '' }}{{ $trend }}%
                     </span>
                     <span>vs bulan lalu</span>
                </div>
            @endif
        </div>
        
        @if($icon)
            <div class="p-3 bg-{{ $color }}-50 rounded-xl text-{{ $color }}-600 shadow-sm group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
