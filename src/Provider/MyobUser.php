<?php

namespace Dcodegroup\LaravelMyobOauth\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class MyobUser implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Creates new resource owner.
     *
     * @param  array  $response
     */
    public function __construct(array $response = [])
    {
        $this->response = json_decode($response, true);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'Uid');
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->getValueByKey($this->response, 'username');
    }

    /**
     * @return mixed
     */
    public function getTokenExpiry()
    {
        return $this->getValueByKey($this->response, 'utc_token_expiry');
    }

    public function toArray(): array
    {
        return $this->response;
    }
}
