<?php

namespace Dcodegroup\LaravelMyobOauth\Provider;

use ErrorException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Provider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    private $cftokenSent = false;

    /*
 * options:
 *     username=xxx
 *     password=xxx
 *     companyName=xxx
 *     clientId=xxx
 *     clientSecret=xxx
 *     redirectUri=xxx
 */

    public function __construct(array $options = [], array $collaborators = [])
    {
        if (! isset($options['username']) || ! isset($options['password']) || ! isset($options['companyName'])) {
            throw new ErrorException('Company Name, username or password not set');
        }

        $this->companyName = $options['companyName'];
        $this->cftoken = base64_encode("{$options['username']}:{$options['password']}");
        parent::__construct($options, $collaborators);
    }

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
     * Check a provider response for errors.
     *
     * @param  ResponseInterface  $response
     * @param  array|string  $data
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        //if ($response->getStatusCode() >= 400) {
        //    throw new UnauthorizedMyob(isset($data['error']) ? $data['error'] : $response->getReasonPhrase(), $response->getStatusCode(), $response);
        //}
        if ($response->getStatusCode() >= 400) {
            $error = $data['ErrorCode'];
            $errorDescription = $data['Errors'][0]['Name']."\n".$data['Errors'][0]['Message'];
            throw new IdentityProviderException(
                "MYOB API Error {$response->getBody()}: {$data['ErrorCode']}: {$data['Errors'][0]['Name']} ({$data['Errors'][0]['Message']})",
                $response->getStatusCode(),
                $response
            );
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
        $headers = ['x-myobapi-version' => 'v2',
            'x-myobapi-key' => $this->clientId, ];
        if (! ($this->cftokenSent)) {
            $headers['x-myobapi-cftoken'] = $this->cftoken;
        }
        $this->cftokenSent = true;

        return $headers;
    }

    protected function getAuthorizationHeaders($token = null): array
    {
        //$headers = [
        //    'x-myobapi-key' => config('laravel-myob-oauth.oauth.client_id'),
        //    'x-myobapi-version' => 'v2',
        //    'Accept' => 'application/json',
        //];
        //
        //if ($token) {
        $headers['Authorization'] = 'Bearer '.$token->getToken();
        //}

        //return $headers;
    }
}
