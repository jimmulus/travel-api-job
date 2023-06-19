<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! auth()->check()) {
            abort(401);
        }

        try {

            /** @var \App\Models\User $user */
            $user = auth()->user();

            switch ($role) {
                case 'admin':
                    if (! $user->roles()->where('name', 'admin')->exists()) {
                        abort(403, 'Not authorized');
                    }

                    return $next($request);
                case 'editor':
                    if (! $user->isEditor()) {
                        abort(403, 'Not authorized');
                    }

                    return $next($request);
                default: abort(403, 'Not authorized');
            }
        } catch (\Exception $e) {
            Log::error($e);
            abort(403);
        }
    }
}
