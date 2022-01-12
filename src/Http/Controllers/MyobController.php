<?php

namespace Dcodegroup\LaravelMyobOauth\Http\Controllers;

use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Dcodegroup\LaravelMyobOauth\MyobService;
use Illuminate\Routing\Controller;

class MyobController extends Controller
{
    public function __construct(protected MyobService $myobService) {
    }

    public function __invoke()
    {
        $latestToken = MyobToken::latestToken();

        $companies = [];
        if ($latestToken) {
            $companies = $this->myobService->getCompanies();
        }

        return view('myob-oauth-views::index', [
            'token' => $latestToken,
            'companies' => $companies,
            'currentCompanyId' => $latestToken->current_tenant_id ?? null,
        ]);
    }
}
