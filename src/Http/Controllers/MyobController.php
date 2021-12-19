<?php

namespace Dcodegroup\LaravelMyobOauth\Http\Controllers;

use App\Http\Controllers\Controller;
use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Dcodegroup\LaravelMyobOauth\MyobTokenService;
use Dcodegroup\LaravelMyobOauth\Provider\Application;

class MyobController extends Controller
{
    public function __construct(
        private Application $myobClient
    ) {
    }

    public function __invoke()
    {
        //dd($this->myobClient);
        //$tenants = [];
        //$token = MyobTokenService::getToken();
        $latestToken = MyobToken::latestToken();
        //if ($token) {
        //    $tenants = $this->myobClient->getTenants($token);
        //}

        return view('myob-oauth-views::index', [
            'token' => $latestToken,
            'user' => $this->myobClient->provider->createResourceOwner(),
            //'tenants' => $tenants,
            //'currentTenantId' => $latestToken->current_tenant_id ?? null,
        ]);
    }
}
