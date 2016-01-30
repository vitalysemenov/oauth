<?php namespace Orchestra\OAuth\Contracts\Command;

use Orchestra\OAuth\Contracts\Listener\ConnectUser;

interface AuthenticateUser
{
    /**
     * Execute user authentication.
     *
     * @param \Orchestra\OAuth\Contracts\Listener\ConnectUser  $listener
     * @param string  $type
     * @param bool  $hasCode
     *
     * @return mixed
     */
    public function execute(ConnectUser $listener, $type, $hasCode = false);
}
