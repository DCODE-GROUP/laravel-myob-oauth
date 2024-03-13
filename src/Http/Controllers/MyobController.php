<?php

namespace Dcodegroup\LaravelMyobOauth\Http\Controllers;

use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Dcodegroup\LaravelMyobOauth\MyobService;
use Dcodegroup\LaravelMyobOauth\MyobTokenService;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

class MyobController extends Controller
{
    public function __construct(protected MyobService $myobService)
    {
    }

    public function __invoke(): View
    {
        $latestToken = MyobTokenService::getToken();

        $companies = [];
        if ($latestToken instanceof MyobToken) {
            $companies = $this->myobService->getCompanies();
        }

        if (config('laravel-myob-oauth.debug')) {
            ld('companies', $companies);
        }

        return view('myob-oauth-views::index', [
            'token' => $latestToken,
            'companies' => $companies,
            'currentCompanyId' => $latestToken->current_tenant_id ?? null,
        ]);
    }
}
