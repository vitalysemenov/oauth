<?php namespace Orchestra\OAuth\Processor;


use Illuminate\Session\Store;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Events\Dispatcher;

use Vitalias\Socials\Contracts\User;
use Vitalias\Socials\Contracts\Provider;
use Vitalias\Socials\Facades\SocialServices as SocialService;

use Orchestra\OAuth\Token;
use Orchestra\OAuth\Contracts\Listener\ConnectUser;
use Orchestra\OAuth\Contracts\Command\AuthenticateUser as Command;
use Orchestra\OAuth\User as Eloquent;

class AuthenticateUser implements Command
{
    /**
     * The authenticator implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * The events dispatcher implementation.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $dispatcher;

    /**
     * The session store implementation.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * The socialite implementation.
     *
     * @var \Laravel\Socialite\Contracts\Factory
     */
    protected $socialService;

    /**
     * Construct a new authenticate user processor.
     *
     * @param \Illuminate\Contracts\Auth\Guard  $auth
     * @param \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     * @param \Illuminate\Session\Store  $session
     * @param \Laravel\Socialite\Contracts\Factory  $socialService
     */


    public function __construct(Guard $auth, Dispatcher $dispatcher, Store $session, SocialService $socialService)
    {


        $this->auth       = $auth;
        $this->dispatcher = $dispatcher;
        $this->session    = $session;
        $this->socialService  = $socialService;
    }

    /**
     * Execute user authentication.
     *
     * @param \Orchestra\OAuth\Contracts\Listener\ConnectUser  $listener
     * @param string  $type
     * @param bool  $hasCode
     *
     * @return mixed
     */
    public function execute(ConnectUser $listener, $type, $hasCode = false)
    {
        $provider = $this->socialService->with($type);

        if (! $hasCode) {
            return $this->getAuthorizationFirst($provider);
        }

        $data = $this->getUserData($provider, $type);

        $this->session->put('authentication.social.oauth', $data);

        return $listener->userHasConnected($data, $this->auth);
    }

    /**
     * Get authorization first from provider.
     *
     * @param  \Laravel\Socialite\Contracts\Provider  $provider
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getAuthorizationFirst(Provider $provider)
    {
        return $provider->redirect();
    }

    /**
     * Get authorization first from provider.
     *
     * @param  \Laravel\Socialite\Contracts\Provider  $provider
     * @param  string  $type
     *
     * @return array
     */
    protected function getUserData(Provider $provider, $type)
    {
        $user = $provider->user();

        $model = $this->attemptToConnectUser($user, $type);

        $data = ['provider' => $type, 'user' => $user];

        $this->dispatcher->fire('authentication.social.oauth.user: saved', [$model, $data, $this->auth]);

        return $data;
    }

    /**
     * Attempt to connect with user authentication.
     *
     * @param  \Laravel\Socialite\Contracts\User  $user
     * @param  string  $type
     *
     * @return \Orchestra\OAuth\User
     */
    protected function attemptToConnectUser(User $user, $type)
    {
        $model = $this->getClientOrCreate($user, $type);

        if (! is_null($currentUser = $this->auth->user())) {
            $model->setAttribute('user_id', $currentUser->getAuthIdentifier());
        }

        $model->setAttribute('token', new Token(['access' => $user->token]));
        $model->save();

        return $model;
    }

    /**
     * @param  \Laravel\Socialite\Contracts\User  $user
     * @param  string  $type
     *
     * @return \Orchestra\OAuth\User
     */
    protected function getClientOrCreate(User $user, $type)
    {
        return Eloquent::firstOrNew([
            'provider' => $type,
            'uid'      => $user->getId(),
        ]);
    }
}
