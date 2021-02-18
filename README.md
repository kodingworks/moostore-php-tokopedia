# Warung BI - Tokopedia

Get product and shop detail from tokopedia

## TOC

- [#requirement](Requirement)
- [#installation](Installation)
- [#basic-usage](Basic Usage)

### Requirement

Lists of required package:

- psr/http-message: ^1.0
- psr/http-client: ^1.0
- guzzlehttp/guzzle: ^7.1
- symfony/http-foundation: ^5.1,
- symfony/psr-http-message-bridge: ^2.0,
- warung/warung-bi: ^dev

### Installation

> Warung BI Base library need to be installed first, to get this package worked

Put this on composer.json to install Warung BI Base library

```json
{
    "require": [
        "warung/warung-bi": "@dev",
        ...
    ],
    "repositories": [
        {
            "type": "git",
            "url": "https://gitlab.com/koding-works/warung/warung-bi/warung-bi.git"
        }
    ],
    ...
}
```

### Basic Usage

#### Initialize

First thing, we need to init the base library

```php
$bi = new BI\BI;
```

Then create the package factory

```php
$tokopedia = $bi->create(\BI\Tokopedia\Factory::class, new \GuzzleHttp\Client);
```

This package include caching for debug use by default, to set the used directory put the following command

```php
$tokopedia->setParameter('cacheDir', __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache');
```

#### Get Shop Detail

To get the shop detail, we need the shop name as the required parameter, then use the following command

```php
$shop = $tokopedia->shop->getDetail(['q' => 'jamkuid'])->send();

var_dump($shop->getResponse()->getData());
```

Use `getResponse()->getData()` method to get the fetched data

This command will give you the shop detail

#### Get Product From URL

To get product detail from an URL, use the following command

```php
$url = 'https://m.tokopedia.com/nature-official/natur-e-advanced-anti-aging-face-wash?xClientId=1691471407.1606521987';
$detail = $tokopedia->product->getFromURL($url)->send();

var_dump($detail->getResponse()->getData());
```

#### Get Product Lists From Shop

This command will only get minimal data product from given shop,
but you can combined below command to get the full version of product's data

```php
$shopID = 3075754;
$products = $tokopedia->product->getFromShopId($shopID)->send();

var_dump($products->getResponse()->getData());
```

### Example

Below is example for getting full version of product detail

```php
$shop = $tokopedia->shop->getDetail(['q' => 'jamkuid'])->send();
$shop = $shop->getResponse()->getData();

$products = $tokopedia->product->getFromShopId($shop->id)->send();
$products = $products->getResponse()->getData();

$data = [];
foreach ($products as $key => $product) {
    $productDetail = $tokopedia->product->getFromURL($product->link)->send();
    $data[] = $productDetail->getResponse()->getData();
}

var_dump($data);
```