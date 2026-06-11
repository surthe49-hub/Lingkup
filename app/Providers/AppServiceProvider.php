<?php

namespace App\Providers;
use App\Services\AI\AiClient;
use App\Services\AI\GeminiClient;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
{
    $this->app->singleton(AiClient::class, function ($app) {
        return new GeminiClient(
            apiKey: config('services.gemini.api_key'),
            model: config('services.gemini.model'),
            baseUrl: config('services.gemini.base_url'),
            timeoutSeconds: config('services.gemini.timeout'),
        );
    });
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define authorization gates
        Gate::define('access-admin', function ($user) {
            return $user->isAdmin();
        });
    }
}