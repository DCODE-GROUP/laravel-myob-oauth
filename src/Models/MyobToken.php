<?php

namespace Dcodegroup\LaravelMyobOauth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

class MyobToken extends Model
{
    /**
     * Fields that are not mass assignable
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return MyobToken
     */
    public static function latestToken()
    {
        return self::latest('id')->first();
    }

    /**
     * @return AccessToken
     */
    public function toOAuth2Token()
    {
        return new AccessToken($this->toArray());
    }

    /**
     * @return bool
     */
    public static function isValidTokenFormat(AccessTokenInterface $token)
    {
        return ! Validator::make($token->jsonSerialize(), [
            'token_type' => 'required',
            'access_token' => 'required',
            'refresh_token' => 'required',
            'expires' => 'required',
            'scope' => 'required',
        ])->fails();
    }
}
