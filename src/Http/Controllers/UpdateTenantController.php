<?php

namespace Dcodegroup\LaravelMyobOauth\Http\Controllers;

use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Dcodegroup\LaravelMyobOauth\MyobService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;

class UpdateTenantController extends Controller
{
    public function __invoke(MyobService $myobService, $id)
    {
        $details = $myobService->getCompanyDetails($id);

        MyobToken::latestToken()->update([
            'current_tenant_id' => Arr::get($details, 'CompanyFile.Uri'),
        ]);

        return redirect()->back();
    }
}
