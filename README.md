# Quickbooks SDK Wrapper For Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/shawnreid/laravel-quickbooks.svg?style=flat-square)](https://packagist.org/packages/shawnreid/laravel-quickbooks)
[![Total Downloads](https://img.shields.io/packagist/dt/shawnreid/laravel-quickbooks.svg?style=flat-square)](https://packagist.org/packages/shawnreid/laravel-quickbooks)
![GitHub Actions](https://github.com/shawnreid/laravel-quickbooks/actions/workflows/main.yml/badge.svg)
![PHP Stan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat)

Laravel Quickbooks is a token manager and wrapper for the [QuickBooks PHP SDK](https://github.com/intuit/QuickBooks-V3-PHP-SDK).
See [Quickbooks API Reference](https://developer.intuit.com/app/developer/qbo/docs/get-started) for more details on how to interact with the API.

## Requirements
|  | Version |
| --- | --- |
| PHP | ^8.1 |
| Laravel | ^9.0 |

## Installation

You can install the package via composer:

```bash
composer require shawnreid/laravel-quickbooks
```

Publish assets
```bash
php artisan vendor:publish --provider="Shawnreid\LaravelQuickbooks\Providers\QuickbooksProvider"
```

Create database table `quickbooks_tokens`
```bash
php artisan migrate
```

## Configuration
1. Before starting you will need a QuickBooks developer account to [setup a sandbox environment](https://developer.intuit.com/app/developer/qbo/docs/develop/sandboxes). You will also need a tool such as [ngrok](https://ngrok.com) to expose your local dev environment.

2. Quickbooks requires a Redirect URI be provided for OAuth2 authentication. You must set this to: ```https://<your_url>/quickbooks/token```

3. Add the appropriate values to your ```.env```

    ```bash
    QUICKBOOKS_CLIENT_ID=<Client ID>
    QUICKBOOKS_CLIENT_SECRET=<Client Secret>
    QUICKBOOKS_API_URL=<Development|Production>
    QUICKBOOKS_DEBUG=<true|false>
    ```
4. By default this package will attach to the ```User``` Model. If you wish to use another model this can be configured in ```configs/laravel-quickbooks.php```.
   A trait will need to be included in the model you wish to use.

   Example:
   ```php
   use Shawnreid\LaravelQuickbooks\Quickbooks;

   class User extends Authenticatable
   {
      use Quickbooks;
   ```
5. The token manager middleware by default is set to ```auth```. Depending on your needs you will likely want to change this.
   This can be configured in ```configs/laravel-quickbooks.php```

## Connecting to Quickbooks

This package provides a simple interface for managing quickbooks OAuth2 connections.

1. Navigate to ```https://<your_url>/quickbooks```
2. Select model you want to attach connection to and click ```Create Connection```. If configured properly you will be redirected to a QuickBooks authentication page.

You may also revoke tokens or refresh tokens from this interface. Note that anytime an API call is made to QuickBooks this package will automatically refresh the token.

## Usage

This package provides syntatic sugar to wrap QuickBooks PHP SDK. Please see [QuickBooks Sample CRUD App](https://github.com/IntuitDeveloper/SampleApp-CRUD-PHP/tree/master/CRUD_Examples) for additional examples.


### Examples
```php
$user = User::find(1);

$user

    // resolve data service
    ->quickbooks()

    // resolve invoice entity
    ->invoice()

    // create invoice
    ->create(
         body: [...]
     )

$user

    // resolve data service
    ->quickbooks()

    // resolve customer entity
    ->customer()

    // update customer
    ->update(
         id: 'id'
         body: [...]
     )

$user

    // resolve data service
    ->quickbooks()

    // resolve bill entity
    ->bill()

    // delete bill
    ->delete(
         id: 'id'
    )

$user

    // resolve data service
    ->quickbooks()

    // resolve vendor entity
     ->vendor()

     // find vendor
     ->find(
         id: 'id'
     )

$user

    // resolve data service
    ->quickbooks()

    // Custom SQL query
    // https://developer.intuit.com/app/developer/qbo/docs/learn/explore-the-quickbooks-online-api/data-queries
    ->query('SELECT * FROM Invoice')
```

### Supported Entities
```php
$user->quickbooks()->account();
$user->quickbooks()->bill();
$user->quickbooks()->billPayment();
$user->quickbooks()->customer();
$user->quickbooks()->estimate();
$user->quickbooks()->invoice();
$user->quickbooks()->item();
$user->quickbooks()->journalEntry();
$user->quickbooks()->payment();
$user->quickbooks()->timeActivity();
$user->quickbooks()->vendor();
$user->quickbooks()->vendorCredit();
$user->quickbooks()->companyCurrency();
$user->quickbooks()->creditMemo();
$user->quickbooks()->department();
$user->quickbooks()->deposit();
$user->quickbooks()->employee();
$user->quickbooks()->purchase();
$user->quickbooks()->purchaseOrder();
$user->quickbooks()->refundReceipt();
$user->quickbooks()->salesReceipt();
$user->quickbooks()->taxAgency();
$user->quickbooks()->taxRate();
$user->quickbooks()->taxService();
$user->quickbooks()->transfer();
```

### Supported CRUD Operations
```php
$user->quickbooks()->invoice()->create([...]);
$user->quickbooks()->invoice()->update('id', [...]);
$user->quickbooks()->invoice()->delete('id');
$user->quickbooks()->invoice()->find('id');
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email shawnreid@gmail.com instead of using the issue tracker.

## Credits

-   [Shawn Reid](https://github.com/shawnreid)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
