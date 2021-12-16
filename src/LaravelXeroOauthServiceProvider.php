<?php

namespace Dcodegroup\LaravelMyobOauth;

use Dcodegroup\LaravelMyobOauth\Commands\InstallCommand;
use Dcodegroup\LaravelMyobOauth\Exceptions\MyobOrganisationExpired;
use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Dcodegroup\LaravelMyobOauth\Provider\Myob;
use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelXeroOauthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->offerPublishing();
        $this->registerRoutes();
        $this->registerResources();
        $this->registerCommands();
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-myob-oauth.php', 'laravel-myob-oauth');

        $this->app->singleton(Myob::class, function () {
            return new Myob([
                'clientId' => config('laravel-myob-oauth.oauth.client_id'),
                'clientSecret' => config('laravel-myob-oauth.oauth.client_secret'),
                'redirectUri' => route(config('laravel-myob-oauth.path').'.callback'),
            ]);
        });

        $this->app->bind(Application::class, function () {
            $client = resolve(myob::class);

            try {
                $token = MyobTokenService::getToken();

                if (! $token) {
                    return new Application('fake_id', 'fake_tenant');
                }

                $latest = MyobToken::latestToken();
            } catch (Exception $e) {
                return new Application('fake_id', 'fake_tenant');
            }

            $tenantId = $latest->current_tenant_id;

            if (is_null($latest->current_tenant_id)) {
                $tenant = head($client->getTenants($token));
                $tenantId = $tenant->tenantId;
            }

            if (! $tenantId) {
                throw new myobOrganisationExpired('There is no configured organisation or the organisation is expired!');
            }

            return new Application($token->getToken(), $tenantId);
        });

        $this->app->bind(BasemyobService::class, function () {
            return new BasemyobService(resolve(Application::class));
        });
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    /**
     * Setup the resource publishing groups for Dcodegroup myob oAuth.
     */
    protected function offerPublishing()
    {
        if (! class_exists('CreatemyobTokensTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../database/migrations/create_myob_tokens_table.stub.php' => database_path('migrations/'.$timestamp.'_create_myob_tokens_table.php'),
            ], 'laravel-myob-oauth-migrations');
        }

        $this->publishes([__DIR__.'/../config/laravel-myob-oauth.php' => config_path('laravel-myob-oauth.php')], 'laravel-myob-oauth-config');
    }

    protected function registerResources()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'myob-oauth-translations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'myob-oauth-views');
    }

    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('laravel-myob-oauth.path'),
            'as' => config('laravel-myob-oauth.path').'.',
            'middleware' => config('laravel-myob-oauth.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/myob.php');
        });
    }
}
