<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Hanya admin yang bisa mengakses halaman log aktivitas
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat mengakses halaman ini.');
        }

        $query = ActivityLog::with('user')->latest();

        // Filter pencarian
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('activity', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter User
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        // Filter Tanggal
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        // Filter Modul
        if ($module = $request->input('module')) {
            $query->where('subject_type', 'like', "%{$module}%");
        }

        $logs = $query->paginate(50)->withQueryString();
        $users = \App\Models\User::all();

        return view('activity-logs.index', compact('logs', 'users'));
    }
}
