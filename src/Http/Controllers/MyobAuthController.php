<?php

namespace Dcodegroup\LaravelMyobOauth\Http\Controllers;

use App\Http\Controllers\Controller;
use Dcodegroup\LaravelMyobOauth\Provider\Provider;
use Illuminate\Http\RedirectResponse;

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
