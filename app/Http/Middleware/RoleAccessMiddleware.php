<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request); // Let the auth middleware handle unauthenticated users
        }

        $role = $user->role;

        // Admin can access everything
        if ($role === 'admin') {
            return $next($request);
        }

        // Exclude common routes like logout, profile management, password updates, and division settings for everyone
        $commonRoutes = [
            'logout',
            'profile.edit',
            'profile.update',
            'profile.destroy',
            'password.update',
            'division.set',
            'division.switch'
        ];
        
        $routeName = $request->route() ? $request->route()->getName() : null;
        if ($routeName && in_array($routeName, $commonRoutes)) {
            return $next($request);
        }

        // Role: Viewer
        // Can access everything but only GET methods (excluding create/edit forms). 
        // We block POST, PUT, PATCH, DELETE, and route names ending in .create or .edit.
        if ($role === 'viewer') {
            $routeName = $request->route() ? $request->route()->getName() : null;
            
            $isCreateOrEditRoute = $routeName && (\Illuminate\Support\Str::endsWith($routeName, '.create') || \Illuminate\Support\Str::endsWith($routeName, '.edit'));

            if (!$request->isMethod('get') && !$request->isMethod('head') || $isCreateOrEditRoute) {
                // If it's an API request, return JSON
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['message' => 'Unauthorized action. Viewers cannot modify data.'], 403);
                }
                
                // Allow specific exceptions like setting division if necessary (or we can block it too)
                if ($request->routeIs('division.set') || $request->routeIs('division.switch')) {
                     return $next($request);
                }

                abort(403, 'Unauthorized action. Viewer tidak diizinkan mengubah, menambah, atau melihat form edit data.');
            }
        }

        // Role: Limited Invoice (Kasir)
        // Can only access specific route prefixes/names
        if ($role === 'limited_invoice') {
            
            // Allow dashboard & division selection
            if ($request->routeIs('dashboard') || $request->routeIs('division.set') || $request->routeIs('division.switch')) {
                return $next($request);
            }

            // Allowed route names pattern
            $allowedRoutes = [
                'invoices.*',
                'products.*',
                'suppliers.*',
                'customers.*',
                'api.products.*',
                'reports.stock',
                'reports.billing*',
                'profile.*'
            ];

            $routeName = $request->route()->getName();
            
            if ($routeName) {
                foreach ($allowedRoutes as $pattern) {
                    if (\Illuminate\Support\Str::is($pattern, $routeName)) {
                        return $next($request);
                    }
                }
            }

            // If we get here, it's not in the allowed list
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Unauthorized access.'], 403);
            }

            abort(403, 'Unauthorized action. Akun ini hanya memiliki akses ke Invoice, Produk, Supplier, dan Customer.');
        }

        // Role: Admin 3
        // Restricted to Invoices, Billing Reports, Products, Suppliers, Customers
        if ($role === 'admin3') {
            if ($request->routeIs('dashboard') || $request->routeIs('division.set') || $request->routeIs('division.switch')) {
                return $next($request);
            }

            $allowedRoutes = [
                'invoices.*',
                'reports.billing*',
                'products.*',
                'suppliers.*',
                'customers.*',
                'api.*',
                'profile.*'
            ];

            $routeName = $request->route() ? $request->route()->getName() : null;
            
            if ($routeName) {
                foreach ($allowedRoutes as $pattern) {
                    if (\Illuminate\Support\Str::is($pattern, $routeName)) {
                        return $next($request);
                    }
                }
            }

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Unauthorized access.'], 403);
            }

            abort(403, 'Unauthorized action. Akun Admin 3 hanya memiliki akses ke Faktur Penjualan, Laporan Tagihan Klien, dan Master Data (Supplier, Produk, Customer).');
        }

        return $next($request);
    }
}
