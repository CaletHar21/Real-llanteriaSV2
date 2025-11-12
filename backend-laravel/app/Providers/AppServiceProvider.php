<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // Logging de consultas para desarrollo: activa solo en local o cuando APP_DEBUG=true
        if (app()->environment('local') || config('app.debug')) {
            DB::listen(function ($query) {
                try {
                    Log::info('sql.query', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time_ms' => $query->time,
                    ]);
                } catch (\Throwable $e) {
                    // no interrumpir la aplicación si hay problemas al loggear
                    Log::warning('Failed to log query: ' . $e->getMessage());
                }
            });
        }
    }
}
