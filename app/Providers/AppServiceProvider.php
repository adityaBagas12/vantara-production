<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('admin-login', function (Request $request): Limit {
            $email = mb_strtolower(trim((string) $request->input('email')));

            return Limit::perMinute(5)
                ->by('admin-login:'.$email.'|'.$request->ip())
                ->response(function (Request $request, array $headers) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam satu menit.',
                        ], 429, $headers);
                    }

                    return redirect()->route('admin.login')
                        ->withInput($request->only('email'))
                        ->withErrors([
                            'email' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam satu menit.',
                        ]);
                });
        });

        RateLimiter::for('invoice', function (Request $request): Limit {
            return Limit::perMinute(30)->by('invoice:'.$request->ip());
        });
    }
}
