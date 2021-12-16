<?php

namespace Dcodegroup\LaravelMyobOauth\Http\Controllers;

use App\Http\Controllers\Controller;
use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Dcodegroup\LaravelMyobOauth\MyobTokenService;
use Dcodegroup\LaravelMyobOauth\Provider\Myob;

class XeroController extends Controller
{
    public function __construct(
        private Myob $myobClient
    ) {
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Dcodegroup\LaravelMyobOauth\Exceptions\UnauthorizedMyob
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function __invoke()
    {
        $tenants = [];
        $token = MyobTokenService::getToken();
        $latestToken = MyobToken::latestToken();
        if ($token) {
            $tenants = $this->myobClient->getTenants($token);
        }

        return view('myob-oauth-views::index', [
            'token' => $latestToken,
            'tenants' => $tenants,
            'currentTenantId' => $latestToken->current_tenant_id ?? null,
        ]);
    }
}
