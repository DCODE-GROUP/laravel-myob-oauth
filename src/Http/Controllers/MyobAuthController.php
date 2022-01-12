<?php

namespace Dcodegroup\LaravelMyobOauth\Http\Controllers;

use Dcodegroup\LaravelMyobOauth\Provider\Provider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

class MyobAuthController extends Controller
{
    public function __construct(private Provider $myobClient) {
    }

    public function __invoke(): RedirectResponse
    {
        return redirect()->to($this->myobClient->getAuthorizationUrl([
            'scope' => [config('laravel-myob-oauth.oauth.scopes')],
        ]));
    }
}
