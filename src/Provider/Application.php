<?php

namespace Dcodegroup\LaravelMyobOauth\Provider;

class Application
{


    public function __construct(
        //protected Provider $provider,
        protected string $token,
        protected ?string $username = null,
        protected ?string $password = null,
    ) {
    }



    public function fetch($uri)
    {
        return $this->provider->getApiResponse($uri, $this->token, $this->username, $this->password);
    }

    public function fetchFullResponse($uri)
    {
        return $this->provider->getFullResponse(
            $uri,
            $this->token,
            $this->username,
            $this->password
        );
    }

    public function fetchWithPagination($uri)
    {
        $result = $this->fetch($uri);
        if (! isset($result->Items)) {
            return $result;
        }

        $items = $result->Items;
        if (! empty($result->NextPageLink)) {
            $result = $this->fetchWithPagination($result->NextPageLink);
            $items = array_merge($items, $result);
        }

        return $items;
    }

    public function post($URI, $data)
    {
        return $this->provider->post('/accountright/'.$URI, $data, $this->token, $this->username, $this->password);
    }

    public function put($URI, $data)
    {
        return $this->provider->put('/accountright/'.$URI, $data, $this->token, $this->username, $this->password);
    }

    public function delete($URI)
    {
        return $this->provider->delete('/accountright/'.$URI, $this->token, $this->username, $this->password);
    }

    public function postFullResponse($URI, $data)
    {
        return $this->provider->postFullResponse('/accountright/'.$URI, $data, $this->token, $this->username, $this->password);
    }
}
