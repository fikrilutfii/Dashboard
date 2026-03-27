<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Abadi Sentosa') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Poppins', 'sans-serif'],
                        },
                        colors: {
                            primary: {
                                50: '#eff6ff',
                                100: '#dbeafe',
                                200: '#bfdbfe',
                                300: '#93c5fd',
                                400: '#60a5fa',
                                500: '#3b82f6',
                                600: '#2563eb',
                                700: '#1d4ed8',
                                800: '#1e40af',
                                900: '#1e3a8a',
                                950: '#172554',
                            }
                        }
                    }
                }
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex bg-gray-50">
            <!-- Left Side: Branding / Image -->
            <div class="hidden lg:flex w-1/2 bg-primary-900 justify-center items-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-30 bg-cover bg-center" style="background-image: url('{{ asset('img/bg-split.jpg') }}');"></div>
                <div class="z-10 text-center text-white p-12">
                    <div class="mb-6 flex justify-center">
                         <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-24 h-24 rounded-full border-4 border-white shadow-lg">
                    </div>
                    <h1 class="text-4xl font-bold tracking-wider uppercase mb-4">Abadi Sentosa</h1>
                    <p class="text-primary-200 text-lg max-w-md mx-auto">Sistem Manajemen Terintegrasi untuk Divisi Percetakan & Konfeksi.</p>
                </div>
            </div>

            <!-- Right Side: Content -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
