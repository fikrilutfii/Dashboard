<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

// Get test user (ID 11: tracker)
$user = User::find(11);
if (!$user) {
    echo "User tracker tidak ditemukan!\n";
    exit(1);
}

echo "=== MENSIMULASIKAN LOGIN ===\n";
Auth::login($user);
ActivityLog::create([
    'user_id' => Auth::id(),
    'activity' => 'login',
    'description' => 'Pengguna berhasil log masuk (Simulasi).',
    'ip_address' => '127.0.0.1',
    'user_agent' => 'PHPUnit/SimulatedBrowser'
]);

echo "=== MENSIMULASIKAN PERUBAHAN USERNAME ===\n";
$oldUsername = $user->username;
$user->username = 'tracker_temp';
$user->save();

ActivityLog::create([
    'user_id' => $user->id,
    'activity' => 'change_username',
    'description' => "Mengubah username dari '{$oldUsername}' menjadi '{$user->username}' (Simulasi).",
    'ip_address' => '127.0.0.1',
    'user_agent' => 'PHPUnit/SimulatedBrowser'
]);

// Revert username back to tracker
$user->username = 'tracker';
$user->save();

echo "=== MENSIMULASIKAN PERUBAHAN PASSWORD ===\n";
ActivityLog::create([
    'user_id' => $user->id,
    'activity' => 'change_password',
    'description' => 'Mengubah password akun (Simulasi).',
    'ip_address' => '127.0.0.1',
    'user_agent' => 'PHPUnit/SimulatedBrowser'
]);

echo "=== MENSIMULASIKAN LOGOUT ===\n";
ActivityLog::create([
    'user_id' => Auth::id(),
    'activity' => 'logout',
    'description' => 'Pengguna log keluar (Simulasi).',
    'ip_address' => '127.0.0.1',
    'user_agent' => 'PHPUnit/SimulatedBrowser'
]);
Auth::logout();

echo "=== DUMPING ACTIVITY LOGS ===\n";
$logs = ActivityLog::latest()->take(4)->get();
foreach ($logs as $log) {
    echo "Time: {$log->created_at}, User: {$log->user->name} ({$log->user->username}), Activity: {$log->activity}, Desc: {$log->description}\n";
}
