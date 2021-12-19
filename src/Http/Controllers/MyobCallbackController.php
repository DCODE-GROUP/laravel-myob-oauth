<?php

namespace Dcodegroup\LaravelMyobOauth\Http\Controllers;

use App\Http\Controllers\Controller;
use Dcodegroup\LaravelMyobOauth\Exceptions\UnauthorizedMyob;
use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Dcodegroup\LaravelMyobOauth\Provider\Provider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MyobCallbackController extends Controller
{
    public function __construct(
        private Provider $myobClient
    ) {
    }

    /**
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     * @throws \Dcodegroup\LaravelMyobOauth\Exceptions\UnauthorizedMyob
     */
    public function __invoke(Request $request): RedirectResponse
    {
        if (! $request->filled('code')) {
            throw new UnauthorizedMyob('Could not authorize MYOB!');
        }

        $token = $this->myobClient->getAccessToken('authorization_code', [
            'code' => $request->input('code'),
        ]);

        if (! MyobToken::isValidTokenFormat($token)) {
            throw new UnauthorizedMyob('Token is invalid or the provided token has invalid format!');
        }

        MyobToken::create($token->jsonSerialize());

        return redirect()->route('myob.index');
    }
}
