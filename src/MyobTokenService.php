<?php

namespace Dcodegroup\LaravelMyobOauth;

use Dcodegroup\LaravelMyobOauth\Exceptions\UnauthorizedMyob;
use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Dcodegroup\LaravelMyobOauth\Provider\Provider;
use Illuminate\Support\Facades\Schema;
use League\OAuth2\Client\Token\AccessToken;

class MyobTokenService
{
    public static function getToken(): ?MyobToken
    {
        if (! Schema::hasTable((new MyobToken())->getTable())) {
            return null;
        }

        $token = MyobToken::latestToken();

        if (! $token instanceof MyobToken) {
            return null;
        }

        $oauth2Token = $token->toOAuth2Token();

        if ($oauth2Token->hasExpired()) {
            $oauth2Token = self::getAccessTokenFromMyob($oauth2Token);

            if (! MyobToken::isValidTokenFormat($oauth2Token)) {
                throw new UnauthorizedMyob('Token is invalid or the provided token has invalid format!');
            }

            $token = $token->replicate()->fill($oauth2Token->jsonSerialize());
            $token->save();
        }

        return $token;
    }

    private static function getAccessTokenFromMyob(AccessToken $token): AccessToken
    {
        return resolve(Provider::class)->getAccessToken('refresh_token', [
            'refresh_token' => $token->getRefreshToken(),
        ]);
    }
}
