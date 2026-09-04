<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::query()->update(['password' => Hash::make('12345678')]);
User::where('name', 'Kasir Faktur')->update(['username' => 'admin_3']);

echo "Passwords updated.";
