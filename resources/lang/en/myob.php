<?php

return [
    'label'  => [
        'header'   => 'MYOB Status',
        'tenants'  => 'Tenants',
        'accounts' => 'Accounts',
        'current_tenant' => 'Current Tenant'
    ],
    'status' => [
        'unauthorized' => 'Token has been expired or MYOB is not authorized',
        'authorized'   => 'MYOB is authorized!',
    ],
    'button' => [
        'authorize' => 'Authorize',
        'select' => 'Select',
    ],
    'message' => [
        'no_tenant_selected' => 'To use MYOB, please select a company from below.'
    ]
];
