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
                darkMode: 'class',
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
            @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap');
            
            body {
                font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif !important;
                background: 
                    radial-gradient(at 0% 0%, rgba(187, 247, 208, 0.5) 0px, transparent 50%),
                    radial-gradient(at 100% 0%, rgba(216, 180, 254, 0.45) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(254, 215, 170, 0.35) 0px, transparent 50%),
                    radial-gradient(at 0% 100%, rgba(165, 243, 252, 0.45) 0px, transparent 50%),
                    #f1f6f4;
                background-attachment: fixed;
                min-height: 100vh;
            }

            [x-cloak] { display: none !important; }

            .glass-card {
                background: rgba(255, 255, 255, 0.78);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.85);
                box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
            }

            .glass-card-hover {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .glass-card-hover:hover {
                transform: translateY(-3px);
                background: rgba(255, 255, 255, 0.9);
                box-shadow: 0 20px 35px -8px rgba(13, 92, 88, 0.08), 0 4px 10px -2px rgba(0, 0, 0, 0.03);
            }

            .glass-sidebar {
                background: rgba(255, 255, 255, 0.58);
                backdrop-filter: blur(24px);
                -webkit-backdrop-filter: blur(24px);
                border-right: 1px solid rgba(255, 255, 255, 0.75);
                box-shadow: 4px 0 24px 0 rgba(15, 23, 42, 0.02);
            }

            .glass-header {
                background: rgba(255, 255, 255, 0.45);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.65);
            }

            .glass-pill {
                background: rgba(255, 255, 255, 0.65);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.85);
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            }

            .coach-active {
                background: linear-gradient(135deg, #0d5c58 0%, #094743 100%) !important;
                color: #ffffff !important;
                box-shadow: 0 10px 20px -3px rgba(13, 92, 88, 0.35) !important;
            }

            .coach-active svg {
                color: #ffffff !important;
            }

            .premium-gradient {
                background: linear-gradient(135deg, #0d5c58 0%, #14b8a6 100%);
            }

            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }

            .scrollbar-hide {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            /* Global Glass Theme Pagination */
            nav[role="navigation"] {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 0.75rem;
            }
            nav[role="navigation"] div:first-child p {
                font-size: 0.75rem;
                color: #64748b;
                font-weight: 600;
            }
            nav[role="navigation"] span.relative,
            nav[role="navigation"] a.relative {
                border-radius: 12px !important;
                margin: 0 2px !important;
                background: rgba(255, 255, 255, 0.85) !important;
                border: 1px solid rgba(226, 232, 240, 0.85) !important;
                color: #334155 !important;
                font-size: 0.75rem !important;
                font-weight: 700 !important;
                padding: 6px 12px !important;
                transition: all 0.2s ease !important;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02) !important;
            }
            nav[role="navigation"] span[aria-current="page"] span {
                background: #0f766e !important;
                color: #ffffff !important;
                border-color: #0f766e !important;
                border-radius: 12px !important;
                box-shadow: 0 4px 10px -2px rgba(15, 118, 110, 0.3) !important;
            }
            nav[role="navigation"] a.relative:hover {
                background: #f0fdfa !important;
                color: #0f766e !important;
                border-color: #99f6e4 !important;
                transform: translateY(-1px);
            }

            /* Smooth Transitions */
            .page-enter {
                animation: slide-up 0.4s ease-out forwards;
            }

            @keyframes slide-up {
                from { opacity: 0; transform: translateY(8px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-800">
        <div x-data="{ 
                sidebarOpen: false, 
                sidebarExpanded: localStorage.getItem('sidebarExpanded') !== 'false' 
            }" 
            x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebarExpanded', value))"
            class="min-h-screen flex relative text-sm"
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
                 class="fixed inset-0 bg-slate-900/30 z-20 lg:hidden backdrop-blur-sm"
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
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 sm:p-6 lg:p-8">
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
