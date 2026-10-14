<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $ua = $request->userAgent() ?? 'Unknown Browser';
        $browser = 'Browser Web';
        if (str_contains($ua, 'Edg')) $browser = 'Microsoft Edge';
        elseif (str_contains($ua, 'Chrome')) $browser = 'Google Chrome';
        elseif (str_contains($ua, 'Firefox')) $browser = 'Mozilla Firefox';
        elseif (str_contains($ua, 'Safari')) $browser = 'Apple Safari';

        $os = 'Desktop';
        if (str_contains($ua, 'Windows')) $os = 'Windows';
        elseif (str_contains($ua, 'Macintosh') || str_contains($ua, 'Mac OS')) $os = 'MacOS';
        elseif (str_contains($ua, 'Linux')) $os = 'Linux';
        elseif (str_contains($ua, 'Android')) $os = 'Android';
        elseif (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) $os = 'iOS';

        \App\Models\ActivityLog::create([
            'user_id'     => Auth::id(),
            'activity'    => 'LOGIN',
            'description' => "Pengguna berhasil masuk dari {$browser} ({$os})",
            'properties'  => [
                'summary' => "Berhasil login ke sistem via {$browser} ({$os})",
                'details' => [
                    ['label' => 'Alamat IP', 'value' => $request->ip() ?? '127.0.0.1'],
                    ['label' => 'Perangkat', 'value' => "{$browser} ({$os})"],
                    ['label' => 'Waktu Masuk', 'value' => now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s')],
                ]
            ],
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent()
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            \App\Models\ActivityLog::create([
                'user_id'     => $user->id,
                'activity'    => 'LOGOUT',
                'description' => 'Pengguna keluar dari sesi sistem.',
                'properties'  => [
                    'summary' => 'Sesi login telah diakhiri (Logout)',
                    'details' => [
                        ['label' => 'Alamat IP', 'value' => $request->ip() ?? '127.0.0.1'],
                        ['label' => 'Waktu Keluar', 'value' => now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i:s')],
                    ]
                ],
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent()
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
