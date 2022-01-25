<?php

namespace Dcodegroup\LaravelMyobOauth\Provider;

use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class Application
{
    protected ?string $baseUrl = null;

    public function __construct(
        protected Provider $provider,
        protected ?MyobToken $token = null,
    ) {
    }

    public function fetch($uri)
    {
        return $this->buildRequest()
            ->get($uri)
            ->json();
    }

    public function fetchFirst($uri)
    {
        return $this->buildRequest()
            ->get($uri)
            ->json('Items.0');
    }

    public function fetchAll($uri)
    {
        $result = $this->fetch($uri);

        $items = Arr::get($result, 'Items', []);

        $nextPageLink = Arr::get($result, 'NextPageLink');
        while (! empty($nextPageLink)) {
            $result = $this->fetch($nextPageLink);
            $items = array_merge($items, Arr::get($result, 'Items', []));
            $nextPageLink = Arr::get($result, 'Items', []);
        }

        return $items;
    }

    public function post($URI, $data)
    {
        return $this->buildRequest()
            ->post($URI, $data)
            ->json();
    }

    public function put($URI, $data)
    {
        return $this->buildRequest()
            ->put($URI, $data)
            ->json();
    }

    public function delete($URI)
    {
        return $this->buildRequest()
            ->delete($URI)
            ->json();
    }

    public function withBaseUrl(string $baseUrl, \Closure $callback)
    {
        $oldBaseUrl = $this->baseUrl;

        $this->baseUrl = $baseUrl;

        $returnValue = $callback($this);

        $this->baseUrl = $oldBaseUrl;

        return $returnValue;
    }

    protected function buildRequest(): PendingRequest
    {
        return Http::withToken($this->token->toOAuth2Token()->getToken())
            ->baseUrl($this->baseUrl ?? $this->token->current_tenant_id)
            ->withHeaders([
                'x-myobapi-key' => config('laravel-myob-oauth.oauth.client_id'),
                'x-myobapi-version' => config('laravel-myob-oauth.api_version'),
            ]);
    }
}
