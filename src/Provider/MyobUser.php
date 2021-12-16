<?php

namespace Dcodegroup\LaravelMyobOauth\Provider;

class MyobUser
{
    public function __construct(
        protected array $response,
    ) {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->response['Uid'];
    }

    /**
     * @return mixed|null
     */
    public function getUsername()
    {
        return $this->response['username'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getTokenExpiry()
    {
        return $this->response['utc_token_expiry'] ?? null;
    }

    public function toArray(): array
    {
        return $this->response;
    }
}
