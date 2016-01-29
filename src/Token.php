<?php namespace VitalySemenov\OAuth;

use Illuminate\Support\Fluent;

class Token extends Fluent
{
    /**
     * Check if token is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return ! empty($this->attributes);
    }
}
