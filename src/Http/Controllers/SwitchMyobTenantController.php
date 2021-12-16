<?php

namespace Dcodegroup\LaravelMyobOauth\Http\Controllers;

use App\Http\Controllers\Controller;
use Dcodegroup\LaravelMyobOauth\Models\MyobToken;
use Illuminate\Http\RedirectResponse;

class SwitchMyobTenantController extends Controller
{
    public function __invoke(string $tenantId): RedirectResponse
    {
        MyobToken::latestToken()->update(['current_tenant_id' => $tenantId]);

        return redirect()->back();
    }
}
