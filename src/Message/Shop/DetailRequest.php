<?php

namespace BI\Tokopedia\Message\Shop;

use BI\Message\AbstractRequest;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class DetailRequest extends AbstractRequest
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
        ];
    }

    public function getData()
    {
        return [
            (object)[
                'operationName' => 'ShopInfoCoreQuery',
                'variables' => [
                    'shopIDs' => [
                        0
                    ],
                    'domain' => $this->parameters->get('q'),
                    'fields' => [
                        'allow_manage',
                        'assets',
                        'core',
                        'favorite',
                        'location',
                        'other-goldos',
                        'other-shiploc',
                        'status',
                        'shipment',
                        'shop-snippet'
                    ]
                ],
                'query' => 'query ShopInfoCoreQuery($shopIDs: [Int!]!, $fields: [String!]!, $domain: String) {
                        shopInfo: shopInfoByID(input: {shopIDs: $shopIDs, fields: $fields, domain: $domain, source: "gql-shoppage-lite"}) {
                        result {
                            favoriteData {
                                totalFavorite
                                alreadyFavorited
                                __typename
                            }
                            goldOS {
                                isGold
                                isGoldBadge
                                isOfficial
                                badge
                                __typename
                            }
                            isAllowManage
                            location
                            shippingLoc {
                                districtName
                                cityName
                                __typename
                            }
                            shopAssets {
                                avatar
                                cover
                                __typename
                            }
                            shopCore {
                                description
                                domain
                                shopID
                                name
                                shopScore
                                tagLine
                                url
                                __typename
                            }
                            statusInfo {
                                shopStatus
                                statusMessage
                                statusTitle
                                __typename
                            }
                            createInfo {
                                shopCreated
                                epochShopCreated
                                openSince
                                __typename
                            }
                            shipmentInfo {
                                isAvailable
                                code
                                image
                                name
                                product {
                                    isAvailable
                                    productName
                                    shipProdID
                                    uiHidden
                                    __typename
                                }
                                isPickup
                                maxAddFee
                                awbStatus
                                __typename
                            }
                            shopSnippetURL
                            customSEO {
                                title
                                description
                                bottomContent
                                __typename
                            }
                            __typename
                        }
                        error {
                                message
                                __typename
                        }
                        __typename
                }}'
            ]
        ];
    }

    public function createResponse($response)
    {
        if (! $this->parameters->get('no-cache', false)) {
            try {
                $f = fopen($this->parameters->get('cacheDir').DIRECTORY_SEPARATOR.'shop-detail-'.$this->parameters->get('q').'-request.html', 'w');
                fwrite($f, json_encode($response));
                fclose($f);
            } catch (\Exception $e) {}
        }

        if ($response) {
            $this->response = new \BI\Tokopedia\Message\Shop\DetailResponse($this, $response);
        }

        return $this;
    }
}
