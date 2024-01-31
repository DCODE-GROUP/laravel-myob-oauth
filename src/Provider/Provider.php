<?php

namespace Dcodegroup\LaravelMyobOauth\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Provider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Returns the base URL for authorizing a client.
     */
    public function getBaseAuthorizationUrl(): string
    {
        return 'https://secure.myob.com/oauth2/account/authorize';
    }

    /**
     * Returns the base URL for requesting an access token.
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
     * Check a provider response for errors.
     *
     * @param  array|string  $data
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            $error = $data['ErrorCode'];
            $errorDescription = $data['Errors'][0]['Name']."\n".$data['Errors'][0]['Message'];
            throw new IdentityProviderException("MYOB API Error {$response->getBody()}: {$data['ErrorCode']}: {$data['Errors'][0]['Name']} ({$data['Errors'][0]['Message']})", $response->getStatusCode(), $response);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): MyobUser
    {
        return new MyobUser($response);
    }

    /**
     * Returns the default headers used by this provider.
     *
     * @return array
     */
    protected function getDefaultHeaders($token = null)
    {
        return [
            'x-myobapi-key' => config('laravel-myob-oauth.oauth.client_id'),
            'x-myobapi-version' => 'v2',
            'Accept' => 'application/json',
        ];
    }

    protected function getAuthorizationHeaders($token = null): array
    {
        return ['Authorization' => 'Bearer '.$token->getToken()];
    }
}
