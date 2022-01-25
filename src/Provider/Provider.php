<?php

namespace Dcodegroup\LaravelMyobOauth\Provider;

use Dcodegroup\LaravelMyobOauth\Exceptions\UnauthorizedMyob;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class Provider extends AbstractProvider
{
    /**
     * Returns the base URL for authorizing a client.
     *
     * @return string
     */
    public function getBaseAuthorizationUrl(): string
    {
        return 'https://secure.myob.com/oauth2/account/authorize';
    }

    /**
     * Returns the base URL for requesting an access token.
     *
     * @param  array  $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://secure.myob.com/oauth2/v1/authorize';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'https://secure.myob.com/oauth2/v1/Validate?scope=CompanyFile';
    }

    protected function getDefaultScopes(): array
    {
        return [
            config('laravel-myob-oauth.oauth.scopes'),
        ];
    }

    /**
     * @param  \Psr\Http\Message\ResponseInterface  $response
     * @param $data
     *
     * @return void
     * @throws \Dcodegroup\LaravelMyobOauth\Exceptions\UnauthorizedMyob
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw new UnauthorizedMyob(isset($data['error']) ? $data['error'] : $response->getReasonPhrase(), $response->getStatusCode(), $response);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): MyobUser
    {
        return new MyobUser($response);
    }

    protected function getAuthorizationHeaders($token = null): array
    {
        $headers = [
            'x-myobapi-key' => config('laravel-myob-oauth.oauth.client_id'),
            'x-myobapi-version' => 'v2',
            'Accept' => 'application/json',
        ];

        if ($token) {
            $headers['Authorization'] = 'Bearer '.$token->getToken();
        }

        return $headers;
    }
}
