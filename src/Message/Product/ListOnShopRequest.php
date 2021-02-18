<?php

namespace BI\Tokopedia\Message\Product;

use BI\Message\AbstractRequest;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class ListOnShopRequest extends AbstractRequest
{
    protected $endpoint = 'https://gql.tokopedia.com/';

    protected $method = 'POST';

    public function __construct(ClientInterface $httpClient = null, RequestInterface $httpRequest = null)
    {
        parent::__construct($httpClient, $httpRequest);
    }

    public function getOptions()
    {
        $options = parent::getOptions();

        $options = array_merge($options, [
            'cookies' => $this->parameters->get('cookieJar'),
            'curl' => [
                CURLOPT_FRESH_CONNECT => 1,
                CURLOPT_FOLLOWLOCATION => 0,
            ],
        ]);

        return $options;
    }

    public function getHeaders()
    {
        return [
            'User-Agent' => 'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Mobile Safari/537.36',
            'Accept' => '*/*',
            'Content-Type' => 'application/json',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Connection' => 'keep-alive',
            'Cache-Control' => 'no-cache',
            'X-Device' => 'tokopedia-lite',
            'X-Source' => 'tokopedia-lite',
            'x-tkpd-lite-service' => 'atreus',
            'Origin' => 'https://m.tokopedia.com',
            'X-Tkpd-Akamai' => 'pdpGetLayout',
        ];
    }

    public function getData()
    {
        return [
            (object)[
                'operationName' => 'ShopProductQuery',
                'variables' => [
                    'shopID' => (string) $this->parameters->get('shopId'),
                    'page' => $this->parameters->get('page', 1),
                    'perPage' => $this->parameters->get('limit', 10),
                    'fkeyword' => '',
                    'fmenu' => 'all',
                    'sort' => 2
                ],
                'query' => 'query ShopProductQuery(
                    $shopID: String!
                    $page: Int
                    $perPage: Int
                    $fkeyword: String
                    $fmenu: String
                    $sort: Int
                    $rating: String
                    $pmin: Int
                    $pmax: Int
                    ) {
                    productList: GetShopProduct(
                        shopID: $shopID
                        filter: {
                        page: $page
                        perPage: $perPage
                        fkeyword: $fkeyword
                        fmenu: $fmenu
                        sort: $sort
                        rating: $rating
                        pmin: $pmin
                        pmax: $pmax
                        }
                    ) {
                        status
                        errors
                        links {
                        self
                        next
                        prev
                        __typename
                        }
                        totalData
                        data {
                        product_id
                        name
                        product_url
                        status
                        price {
                            text_idr
                            __typename
                        }
                        flags {
                            isFeatured
                            isPreorder
                            isWishlist
                            isWholesale
                            isFreereturn
                            mustInsurance
                            supportFreereturn
                            withStock
                            isSold
                            __typename
                        }
                        label {
                            icon
                            color_hex
                            color_rgb
                            content
                            __typename
                        }
                        label_groups {
                            position
                            title
                            type
                            __typename
                        }
                        badge {
                            title
                            image_url
                            __typename
                        }
                        stats {
                            reviewCount
                            rating
                            __typename
                        }
                        primary_image {
                            thumbnail
                            __typename
                        }
                        cashback {
                            cashback
                            cashback_amount
                            __typename
                        }
                        campaign {
                            is_active
                            original_price
                            original_price_fmt
                            discounted_percentage
                            __typename
                        }
                        __typename
                        }
                        __typename
                    }
                }
                '
            ],
        ];
    }

    public function createResponse($response)
    {
        if (! $this->parameters->get('no-cache', false)) {
            try {
                $f = fopen($this->parameters->get('cacheDir').DIRECTORY_SEPARATOR.'product-list-on-shop-'.$this->parameters->get('shopId').'-request.html', 'w');
                fwrite($f, json_encode($response));
                fclose($f);
            } catch (\Exception $e) {}
        }

        if ($response) {
            $response = (object)[
                'metadata' => (object) [
                    'page' => $this->parameters->get('page', 1),
                    'limit' => $this->parameters->get('limit', 10),
                ],
                'response' => $response,
            ];
            $this->response = new \BI\Tokopedia\Message\Product\ListOnShopResponse($this, $response);
        }

        return $this;
    }
}
