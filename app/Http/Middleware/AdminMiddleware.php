<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || ! Auth::user()->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized admin access.'], 403);
            }

            return redirect()->route('admin.login')
                ->with('error', 'Silakan masuk dengan akun Admin Vantara Production terlebih dahulu.');
        }

        return $next($request);
    }
}
