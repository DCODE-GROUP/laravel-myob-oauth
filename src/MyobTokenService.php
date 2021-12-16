<?php

namespace Dcodegroup\LaravelMyobOauth;

use Calcinai\OAuth2\Client\Provider\Xero;
use Dcodegroup\LaravelMyobOauth\Exceptions\UnauthorizedMyob;
use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Illuminate\Support\Facades\Schema;
use League\OAuth2\Client\Token\AccessToken;

class MyobTokenService
{
    /**
     * @return null|\League\OAuth2\Client\Token\AccessToken|mixed
     *@throws \Dcodegroup\LaravelMyobOauth\Exceptions\UnauthorizedMyob
     */
    public static function getToken()
    {
        if (! Schema::hasTable((new MyobToken())->getTable())) {
            return null;
        }

        $token = MyobToken::latestToken();

        if (! $token) {
            return null;
        }

        $oauth2Token = $token->toOAuth2Token();

        if ($oauth2Token->hasExpired()) {
            $oauth2Token = self::getAccessTokenFromXero($oauth2Token);

            if (! MyobToken::isValidTokenFormat($oauth2Token)) {
                throw new UnauthorizedMyob('Token is invalid or the provided token has invalid format!');
            }

            MyobToken::create(array_merge($oauth2Token->jsonSerialize(), ['current_tenant_id' => $token->current_tenant_id]));
        }

        return $oauth2Token;
    }

    /**
     * @return mixed
     */
    private static function getAccessTokenFromXero(AccessToken $token)
    {
        return resolve(Xero::class)->getAccessToken('refresh_token', [
            'refresh_token' => $token->getRefreshToken(),
        ]);
    }
}
