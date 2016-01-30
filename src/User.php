<?php namespace VitalySemenov\OAuth;

use Orchestra\Model\Eloquent;

class User extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_oauth';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['provider', 'uid'];

    /**
     * Get token attribute using accessor.
     *
     * @param  mixed  $value
     *
     * @return \VitalySemenov\OAuth\Token
     */
    public function getTokenAttribute($value)
    {
        if (! is_null($value)) {
            $value = json_decode($value, true);
        }

        return new Token($value);
    }

    /**
     * Set token attribute using mutator.
     *
     * @param  \VitalySemenov\OAuth\Token  $token
     *
     * @return void
     */
    public function setTokenAttribute(Token $token)
    {
        $value = null;

        if (! $token->isValid()) {
            $value = $token->toJson();
        }

        $this->attributes['token'] = $value;
    }
}
