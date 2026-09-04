@props(['title' => '', 'subtitle' => '', 'headerActions' => null])

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $title }}</h2>
                @if($subtitle)
                    <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($headerActions))
                <div class="flex items-center gap-2">
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{ $slot }}
        </div>
    </div>
</x-app-layout>
