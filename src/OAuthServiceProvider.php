<?php namespace VitalySemenov\OAuth;

use Orchestra\Support\Providers\ServiceProvider;

class OAuthServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['events']->listen('auth.login', 'VitalySemenov\OAuth\Handlers\UserLoggedIn');
        $this->app['events']->listen('orchestra.oneauth.user: saved', 'VitalySemenov\OAuth\Handlers\UserConnected');
        $this->app['events']->listen('auth.logout', 'VitalySemenov\OAuth\Handlers\UserLoggedOut');
    }
}
