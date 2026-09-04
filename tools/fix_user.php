<?php
\App\Models\User::where('email', 'faktur@example.com')->update(['role' => 'limited_invoice']);
echo "Role is now: " . \App\Models\User::where('email', 'faktur@example.com')->first()->role . "\n";
