# Laravel MYOB

This package provides the standard MYOB connection functionality used in most projects.

## Installation

You can install the package via composer:

```bash
composer require dcodegroup/laravel-myob-oauth
```

Then run the install command.

```bash
php artsian laravel-myob:install
```

This will publish the configuration file and the migration file.

Run the migrations

```bash
php artsian migrate
```

## Configuration

Most of configuration has been set the fair defaults. However you can review the configuration file at `config/laravel-myob-oauth.php` and adjust as needed


## Usage

The package provides an endpoints which you can use. See the full list by running
```bash
php artsian route:list --name=myob
```

```
+--------+----------+-------------------------+--------------------+-------------------------------------------------------------------------+----------------------------------+
| Domain | Method   | URI                     | Name               | Action                                                                  | Middleware                       |
+--------+----------+-------------------------+--------------------+-------------------------------------------------------------------------+----------------------------------+
|        | GET|HEAD | myob                    | myob.index         | Dcodegroup\LaravelMyobOauth\Http\Controllers\XeroController             | web                              |
|        |          |                         |                    |                                                                         | App\Http\Middleware\Authenticate |
|        | GET|HEAD | myob/auth               | xero.auth          | Dcodegroup\LaravelMyobOauth\Http\Controllers\XeroAuthController         | web                              |
|        |          |                         |                    |                                                                         | App\Http\Middleware\Authenticate |
|        | GET|HEAD | myob/callback           | xero.callback      | Dcodegroup\LaravelMyobOauth\Http\Controllers\XeroCallbackController     | web                              |
|        |          |                         |                    |                                                                         | App\Http\Middleware\Authenticate |
|        | POST     | myob/tenants/{tenantId} | xero.tenant.update | Dcodegroup\LaravelMyobOauth\Http\Controllers\SwitchXeroTenantController | web                              |
|        |          |                         |                    |                                                                         | App\Http\Middleware\Authenticate |
+--------+----------+-------------------------+--------------------+-------------------------------------------------------------------------+----------------------------------+
```

More Information

`example.com/myob` Which is where you will generate the link to authorise MYOB. This is by default protected auth middleware but you can modify in the configuration. This is where you want to link to in your admin and possibly a new window

`example.com/myob/callback` This is the route for which xero will redirect back tp after the oauth has occurred. This is excluded from the middleware auth. You can change this list in the configuration also.

## BaseMyobService

The package has a `BaseMyobService` class located at `Dcodegroup\LaravelMyobOauth\BaseMyobService` 

So your application should have its own MyobService extend this base class as the initialisation is already done.

```php
<?php

namespace App\Services\Myob;

use Dcodegroup\LaravelMyobOauth\MyobService;
use XeroPHP\Models\Accounting\Contact;

class MyobService extends MyobService
{
    /**
     * @inheritDoc
     */
    public function createContact(object $data)
    {
    
        /**
         * $this->>xeroClient is inherited from the  BaseXeroService
         */
        $contact = new Contact($this->xeroClient);

        $contact->setName($data->name . ' (' . $data->code . ')')
            ->setFirstName($data->name)
            ->setContactNumber($data->code)
            ->setAccountNumber($data->code)
            ->setContactStatus(Contact::CONTACT_STATUS_ACTIVE)
            ->setEmailAddress($data->email)
            ->setTaxNumber('ABN')
            ->setDefaultCurrency('AUD');

        $contact = head($contact->save()->getElements());

        return $this->xeroClient->loadByGUID(Contact::class, $contact['ContactID']);
    }

}
```

