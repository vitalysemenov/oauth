<?php namespace VitalySemenov\OAuth\Contracts\Command;

use VitalySemenov\OAuth\Contracts\Listener\ConnectUser;

interface AuthenticateUser
{
    /**
     * Execute user authentication.
     *
     * @param \VitalySemenov\OAuth\Contracts\Listener\ConnectUser  $listener
     * @param string  $type
     * @param bool  $hasCode
     *
     * @return mixed
     */
    public function execute(ConnectUser $listener, $type, $hasCode = false);
}
