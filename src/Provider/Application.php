<?php

namespace Dcodegroup\LaravelMyobOauth\Provider;

use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Illuminate\Http\Client\PendingRequest;
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

//    public function fetchWithPagination($uri)
//    {
//        $result = $this->fetch($uri);
//        if (! isset($result->Items)) {
//            return $result;
//        }
//
//        $items = $result->Items;
//        if (! empty($result->NextPageLink)) {
//            $result = $this->fetchWithPagination($result->NextPageLink);
//            $items = array_merge($items, $result);
//        }
//
//        return $items;
//    }

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
                'x-myobapi-version' => config('laravel-myob-oauth.api_version')
            ]);
    }
}
