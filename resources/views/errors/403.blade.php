<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak (403) - {{ config('app.name', 'Mini ERP') }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(244, 63, 94, 0.08) 0px, transparent 50%), 
                radial-gradient(at 100% 0%, rgba(65, 91, 243, 0.08) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(251, 191, 36, 0.05) 0px, transparent 50%);
        }
        .premium-shadow {
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.07), 0 15px 25px -10px rgba(0, 0, 0, 0.03);
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white/70 backdrop-blur-md border border-white/50 rounded-3xl p-8 text-center premium-shadow transition-all hover:shadow-2xl">
        <!-- Icon -->
        <div class="relative w-24 h-24 mx-auto mb-6 flex items-center justify-center bg-red-50 rounded-2xl animate-float">
            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            <div class="absolute inset-0 bg-red-400/20 blur-xl rounded-full scale-75 -z-10"></div>
        </div>

        <!-- Heading -->
        <h1 class="text-7xl font-extrabold text-slate-800 tracking-tight mb-2">403</h1>
        <h2 class="text-xl font-bold text-slate-700 mb-4">Akses Ditolak</h2>
        
        <!-- Error Message -->
        <div class="bg-red-50/50 border border-red-100 rounded-2xl p-4 mb-8">
            <p class="text-sm font-medium text-red-600">
                {{ $exception->getMessage() ?: 'Maaf, akun Anda tidak memiliki izin untuk mengakses halaman ini.' }}
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button onclick="history.back()" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 px-6 rounded-2xl transition-all active:scale-95">
                Kembali
            </button>
            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-2xl transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 active:scale-95">
                Menu Utama
            </a>
        </div>
    </div>
</body>
</html>
