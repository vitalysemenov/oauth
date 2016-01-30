<?php namespace Orchestra\OAuth;

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
        $this->app['events']->listen('auth.login', 'Orchestra\OAuth\Handlers\UserLoggedIn');
        $this->app['events']->listen('authentication.social.oauth.user: saved', 'Orchestra\OAuth\Handlers\UserConnected');
        $this->app['events']->listen('auth.logout', 'Orchestra\OAuth\Handlers\UserLoggedOut');
    }
}
