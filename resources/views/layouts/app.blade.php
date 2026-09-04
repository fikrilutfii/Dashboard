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
                                50: '#f5f7ff',
                                100: '#ebf0fe',
                                200: '#ced9fd',
                                300: '#a1b6fb',
                                400: '#6c89f7',
                                500: '#415bf3',
                                600: '#2c3edb',
                                700: '#2330b3',
                                800: '#212a91',
                                900: '#1e2675',
                                950: '#121644',
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
                        },
                        boxShadow: {
                            'premium': '0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 5px 15px -5px rgba(0, 0, 0, 0.03)',
                            'premium-hover': '0 20px 40px -5px rgba(0, 0, 0, 0.08), 0 10px 20px -5px rgba(0, 0, 0, 0.05)',
                        }
                    }
                }
            }
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');
            
            body {
                font-family: 'Outfit', sans-serif !important;
            }

            [x-cloak] { display: none !important; }

            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            .glass-dark {
                background: rgba(24, 24, 27, 0.8);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .premium-gradient {
                background: linear-gradient(135deg, #415bf3 0%, #7084f8 100%);
            }

            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }

            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            /* Smooth Transitions */
            .page-enter {
                animation: slide-up 0.4s ease-out forwards;
            }

            @keyframes slide-up {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
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

        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Tutup',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-lg'
                    }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Tutup',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-lg'
                    }
                });
            @endif

            document.addEventListener('DOMContentLoaded', function() {
                const actionForms = document.querySelectorAll('.action-confirm, .delete-confirm');
                actionForms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const title = form.getAttribute('data-title') || 'Apakah Anda yakin?';
                        const text = form.getAttribute('data-text') || 'Data yang dihapus tidak dapat dikembalikan!';
                        const icon = form.getAttribute('data-icon') || 'warning';
                        const confirmText = form.getAttribute('data-confirm-text') || 'Ya, Hapus!';
                        const confirmColor = form.getAttribute('data-confirm-color') || '#ef4444';

                        Swal.fire({
                            title: title,
                            text: text,
                            icon: icon,
                            showCancelButton: true,
                            confirmButtonColor: confirmColor,
                            cancelButtonColor: '#9ca3af',
                            confirmButtonText: confirmText,
                            cancelButtonText: 'Batal',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'rounded-lg',
                                cancelButton: 'rounded-lg'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });

            function confirmPrint(url, isDownload = false) {
                Swal.fire({
                    title: 'Cetak Dokumen?',
                    text: 'Anda akan mencetak/mengunduh dokumen ini.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Cetak!',
                    confirmButtonColor: '#3b82f6',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-lg',
                        cancelButton: 'rounded-lg'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        if(isDownload) {
                            window.location.href = url;
                        } else {
                            window.open(url, '_blank');
                        }
                        
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Dokumen sedang diproses.',
                                confirmButtonColor: '#3b82f6',
                                customClass: {
                                    popup: 'rounded-2xl',
                                    confirmButton: 'rounded-lg'
                                }
                            }).then(() => window.location.reload());
                        }, 1000);
                    }
                });
            }
        </script>
        @stack('scripts')
    </body>
</html>
