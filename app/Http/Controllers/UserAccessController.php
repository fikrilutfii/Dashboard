<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserAccessController extends Controller
{
    private function authorizeTracker(Request $request): void
    {
        abort_unless($request->user()?->username === 'tracker', 403, 'Hanya akun tracker yang dapat mengatur akses pengguna.');
    }

    public function index(Request $request): View
    {
        $this->authorizeTracker($request);

        return view('user-access.index', [
            'users' => User::orderBy('name')->orderBy('username')->get(),
            'roles' => [
                'admin' => 'Admin penuh',
                'limited_invoice' => 'Invoice terbatas',
                'admin3' => 'Laporan tagihan klien',
                'viewer' => 'Viewer (hanya lihat)',
            ],
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeTracker($request);

        abort_if($user->username === 'tracker', 422, 'Akses akun tracker tidak dapat diubah dari halaman ini.');

        $validated = $request->validate([
            'role' => 'required|in:admin,limited_invoice,admin3,viewer',
            'allowed_division' => 'required|in:all,percetakan,konfeksi,peternakan',
        ]);

        $user->update($validated);

        return back()->with('success', "Akses {$user->username} berhasil diperbarui.");
    }
}
