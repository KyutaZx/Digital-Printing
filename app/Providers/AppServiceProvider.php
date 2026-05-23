<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('layouts.manager', function ($view) {
            $pendingCount = 0;
            try {
                if (session()->has('token')) {
                    $apiUrl = config('app.golang_api_url', 'http://localhost:8080');
                    $r = \Illuminate\Support\Facades\Http::timeout(3)->withToken(session('token'))->get("{$apiUrl}/api/orders/all?limit=100");
                    if ($r->successful()) {
                        $orders = $r->json('data') ?? $r->json() ?? [];
                        if (is_array($orders)) {
                            $pendingCount = count(array_filter($orders, fn($o) => ($o['status'] ?? '') === 'payment_verification'));
                        }
                    }
                }
            } catch (\Exception $e) {}
            
            $view->with('pendingVerifikasiCount', $pendingCount);
        });
    }
}
