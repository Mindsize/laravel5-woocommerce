# Laravel 5 WooCommerce API Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mindsize/laravel5-woocommerce.svg?style=flat-square)](https://packagist.org/packages/mindsize/laravel5-woocommerce)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Travis Build](https://img.shields.io/travis/Mindsize/laravel5-woocommerce/master.svg?style=flat-square)](https://travis-ci.org/Mindsize/laravel5-woocommerce)
[![Total Downloads](https://img.shields.io/packagist/dt/mindsize/laravel5-woocommerce.svg?style=flat-square)](https://packagist.org/packages/mindsize/laravel5-woocommerce)

A simple Laravel 5 wrapper for the [official WooCommerce REST API PHP Library](https://github.com/woothemes/wc-api-php) from Automattic.

## Installation

### Step 1: Install Through Composer

``` bash
composer require mindsize/laravel5-woocommerce
```

### Step 2: Publish configuration
``` bash
php artisan vendor:publish --provider="Mindsize\WC\ServiceProvider"
```

### Step 3: Customize configuration
You can directly edit the configuration in `config/woocommerce.php` or copy these values to your `.env` file.
```php
WC_STORE_URL=http://example.org
WC_CONSUMER_KEY=ck_your-consumer-key
WC_CONSUMER_SECRET=cs_your-consumer-secret
WC_VERIFY_SSL=false
WC_VERSION=v1
WC_WP_API=true
WC_WP_QUERY_STRING_AUTH=false
WC_WP_TIMEOUT=15
```

## Examples

### Get the index of all available endpoints
```php
use WC;

return WC::get('');
```

### View all orders
```php
use WC;

return WC::get('orders');
```

### View all completed orders created after a specific date
#### For legacy API versions 
(WC 2.4.x or later, WP 4.1 or later) use this syntax

```php
use WC;

$data = [
    'status' => 'completed',
    'filter' => [
        'created_at_min' => '2016-01-14'
    ]
];

$result = WC::get('orders', $data);

foreach($result['orders'] as $order)
{
    // do something with $order
}

// you can also use array access
$orders = WC::get('orders', $data)['orders'];

foreach($orders as $order)
{
    // do something with $order
}
```

#### For current API versions 
(WC 2.6.x or later, WP 4.4 or later) use this syntax.
`after` needs to be a ISO-8601 compliant date!≠

```php
use WC;

$data = [
    'status' => 'completed',
    'after' => '2016-01-14T00:00:00'
    ]
];

$result = WC::get('orders', $data);

foreach($result['orders'] as $order)
{
    // do something with $order
}

// you can also use array access
$orders = WC::get('orders', $data)['orders'];

foreach($orders as $order)
{
    // do something with $order
}
```

### Update a product
```php
use WC;

$data = [
    'product' => [
        'title' => 'Updated title'
    ]
];

return WC::put('products/1', $data);
```

### Pagination
So you don't have to mess around with the request and response header and the calculations this wrapper will do all the heavy lifting for you.
(WC 2.6.x or later, WP 4.4 or later) 

```php
use WC;

// assuming we have 474 orders in pur result
// we will request page 5 with 25 results per page
$params = [
    'per_page' => 25,
    'page' => 5
];

WC::get('orders', $params);

WC::totalResults(); // 474
WC::firstPage(); // 1
WC::lastPage(); // 19
WC::currentPage(); // 5 
WC::totalPages(); // 19
WC::previousPage(); // 4
WC::nextPage(); // 6
WC::hasPreviousPage(); // true 
WC::hasNextPage(); // true
WC::hasNotPreviousPage(); // false 
WC::hasNotNextPage(); // false
```

### HTTP Request & Response (Headers)

```php
use WC;

// first send a request
WC::get('orders');

// get the request
WC::getRequest();

// get the response headers
WC::getResponse();

// get the total number of results
WC::getResponse()->getHeaders()['X-WP-Total']
```


### More Examples
Refer to [WooCommerce REST API Documentation](https://woocommerce.github.io/woocommerce-rest-api-docs) for more examples and documention.

## Testing
Run the tests with:
```bash
vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Forked from [pixelpeter/laravel5-woocommerce-api-client](https://github.com/pixelpeter/laravel5-woocommerce-api-client)

Thanks Peter!