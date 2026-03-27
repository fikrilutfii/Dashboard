<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            primary: {
                                50: '#eef2ff',
                                100: '#e0e7ff',
                                200: '#c7d2fe',
                                300: '#a5b4fc',
                                400: '#818cf8',
                                500: '#6366f1',
                                600: '#4f46e5',
                                700: '#4338ca',
                                800: '#3730a3',
                                900: '#312e81',
                                950: '#1e1b4b',
                            },
                             zinc: {
                                50: '#fafafa',
                                100: '#f4f4f5',
                                200: '#e4e4e7',
                                300: '#d4d4d8',
                                400: '#a1a1aa',
                                500: '#71717a',
                                600: '#52525b',
                                700: '#3f3f46',
                                800: '#27272a',
                                900: '#18181b',
                                950: '#09090b',
                            }
                        }
                    }
                }
            }
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        
        <style>
             [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-100">
        <div x-data="{ 
                sidebarOpen: false, 
                sidebarExpanded: localStorage.getItem('sidebarExpanded') !== 'false' 
            }" 
            x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebarExpanded', value))"
            class="min-h-screen bg-gray-100 flex relative text-sm"
        >
            
            <!-- Mobile Sidebar Overlay -->
            <div x-show="sidebarOpen" 
                 @click="sidebarOpen = false" 
                 x-transition:enter="transition-opacity ease-linear duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="transition-opacity ease-linear duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden glass"
                 x-cloak
            ></div>

            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 overflow-hidden">
                <!-- Topbar -->
                <x-topbar>
                    <x-slot name="header">
                         {{ $header ?? '' }}
                    </x-slot>
                </x-topbar>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
