<?php

namespace Dcodegroup\LaravelMyobOauth;

use Dcodegroup\LaravelMyobOauth\Provider\Application;
use Illuminate\Support\Collection;

class MyobService
{
    public function __construct(public Application $myobClient) {}

    public function getCompanies(): Collection
    {
        $response = $this->myobClient
            ->withBaseUrl(
                'https://api.myob.com',
                fn (Application $client) => $client->fetch('/accountright')
            );

        return collect($response);
    }

    public function getCompanyDetails($id): array
    {
        return $this->myobClient
            ->withBaseUrl(
                'https://api.myob.com',
                fn (Application $client) => $client->fetch("/accountright/{$id}")
            );
    }

    public function getClientByDisplayID(string $displayId)
    {
        return $this->myobClient->fetchFirst("/Contact/Customer?\$filter=DisplayID eq '$displayId'");
    }

    public function getTaxCodes(): Collection
    {
        return collect($this->myobClient->fetch('/GeneralLedger/TaxCode'));
    }

    public function getInvoiceByNumber(string $type, string $number)
    {
        return $this->myobClient->fetchFirst("/Sale/Invoice/$type?\$filter=Number eq '$number'");
    }

    public function getAccounts(?string $type = null): Collection
    {
        $url = '/GeneralLedger/Account';

        if ($type) {
            $url .= "?\$filter=Classification eq '$type'";
        }

        return collect($this->myobClient->fetchAll($url));
    }
}
