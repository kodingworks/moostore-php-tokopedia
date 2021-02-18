<?php

namespace BI\Tokopedia\Message\Product;

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
            'X-Tkpd-Akamai' => 'pdpGetLayout',
        ];
    }

    public function getData()
    {
        $shopName = '';
        $productName = '';

        $url = parse_url($this->parameters->get('url'));
        if (isset($url['path'])) {
            $path = trim($url['path'], '/');
            $expPath = explode('/', $path);

            $shopName = $expPath[0];
            $productName = $expPath[1];
        }

        return [
            (object)[
                'operationName' => 'PDPGetLayoutQuery',
                'variables' => [
                    'shopDomain' => $shopName,
                    'productKey' => $productName,
                    'layoutID' => '',
                    'apiVersion' => 1
                ],
                'query' => '
                    fragment ProductVariant on pdpDataProductVariant {
                        errorCode
                        parentID
                        defaultChild
                        sizeChart
                        variants {
                            productVariantID
                            variantID
                            name
                            identifier
                            option {
                            picture {
                                url
                                url100
                                __typename
                            }
                            productVariantOptionID
                            variantUnitValueID
                            value
                            hex
                            __typename
                            }
                            __typename
                        }
                        children {
                            productID
                            price
                            priceFmt
                            sku
                            optionID
                            productName
                            productURL
                            picture {
                            url
                            url100
                            __typename
                            }
                            stock {
                            stock
                            isBuyable
                            stockWording
                            stockWordingHTML
                            minimumOrder
                            maximumOrder
                            __typename
                            }
                            isCOD
                            isWishlist
                            campaignInfo {
                            campaignID
                            campaignType
                            campaignTypeName
                            discountPercentage
                            originalPrice
                            discountPrice
                            stock
                            stockSoldPercentage
                            threshold
                            startDate
                            endDate
                            endDateUnix
                            appLinks
                            isAppsOnly
                            isActive
                            hideGimmick
                            minOrder
                            __typename
                            }
                            __typename
                        }
                        __typename
                        }
                        fragment ProductMedia on pdpDataProductMedia {
                        media {
                            type
                            suffix
                            prefix
                            urlOriginal: URLOriginal
                            videoUrl: videoURLAndroid
                            description
                            __typename
                        }
                        videos {
                            source
                            url
                            __typename
                        }
                        __typename
                        }
                        fragment ProductUpcomingCampaign on pdpDataUpcomingCampaign {
                        campaignID
                        campaignType
                        campaignTypeName
                        startDate
                        endDate
                        notifyMe
                        ribbonCopy
                        upcomingType
                        __typename
                        }
                        fragment ProductHighlight on pdpDataProductContent {
                        name
                        price {
                            value
                            currency
                            __typename
                        }
                        campaign {
                            campaignID
                            campaignType
                            campaignTypeName
                            percentageAmount
                            originalPrice
                            discountedPrice
                            originalStock
                            stock
                            stockSoldPercentage
                            threshold
                            startDate
                            endDate
                            endDateUnix
                            appLinks
                            isAppsOnly
                            isActive
                            hideGimmick
                            __typename
                        }
                        stock {
                            useStock
                            value
                            stockWording
                            __typename
                        }
                        variant {
                            isVariant
                            parentID
                            __typename
                        }
                        wholesale {
                            minQty
                            price {
                            value
                            currency
                            __typename
                            }
                            __typename
                        }
                        isCashback {
                            percentage
                            __typename
                        }
                        isFreeOngkir {
                            isActive
                            imageURL
                            __typename
                        }
                        isTradeIn
                        isOS
                        isPowerMerchant
                        isWishlist
                        isCOD
                        preorder {
                            duration
                            timeUnit
                            isActive
                            __typename
                        }
                        __typename
                        }
                        fragment ProductCustomInfo on pdpDataCustomInfo {
                        icon
                        title
                        isApplink
                        applink
                        separator
                        description
                        __typename
                        }
                        fragment ProductInfo on pdpDataProductInfo {
                        row
                        content {
                            title
                            subtitle
                            applink
                            __typename
                        }
                        __typename
                        }
                        fragment ProductDataInfo on pdpDataInfo {
                        icon
                        title
                        isApplink
                        applink
                        content {
                            icon
                            text
                            __typename
                        }
                        __typename
                        }
                        fragment ProductSocial on pdpDataSocialProof {
                        row
                        content {
                            icon
                            title
                            subtitle
                            applink
                            type
                            rating
                            __typename
                        }
                        __typename
                        }
                        fragment ProductDetail on pdpDataProductDetail {
                        content {
                            title
                            subtitle
                            applink
                            showAtFront
                            isAnnotation
                            __typename
                        }
                        __typename
                        }
                        query PDPGetLayoutQuery(
                        $shopDomain: String
                        $productKey: String
                        $layoutID: String
                        $apiVersion: Float
                        ) {
                        pdpGetLayout(
                            shopDomain: $shopDomain
                            productKey: $productKey
                            layoutID: $layoutID
                            apiVersion: $apiVersion
                        ) {
                            name
                            pdpSession
                            basicInfo {
                            alias
                            id: productID
                            shopID
                            shopName
                            minOrder
                            maxOrder
                            weight
                            weightUnit
                            condition
                            status
                            url
                            sku
                            gtin
                            isMustInsurance
                            needPrescription
                            catalogID
                            isLeasing
                            isBlacklisted
                            menu {
                                id
                                name
                                url
                                __typename
                            }
                            category {
                                id
                                name
                                title
                                breadcrumbURL
                                isAdult
                                lastUpdateCategory
                                detail {
                                id
                                name
                                breadcrumbURL
                                isAdult
                                __typename
                                }
                                __typename
                            }
                            blacklistMessage {
                                title
                                description
                                button
                                url
                                __typename
                            }
                            txStats {
                                transactionSuccess
                                transactionReject
                                countSold
                                paymentVerified
                                itemSoldPaymentVerified
                                __typename
                            }
                            stats {
                                countView
                                countReview
                                countTalk
                                rating
                                __typename
                            }
                            __typename
                            }
                            components {
                            name
                            type
                            data {
                                ...ProductMedia
                                ...ProductHighlight
                                ...ProductInfo
                                ...ProductSocial
                                ...ProductDataInfo
                                ...ProductUpcomingCampaign
                                ...ProductCustomInfo
                                ...ProductDetail
                                ...ProductVariant
                                __typename
                            }
                            __typename
                            }
                            __typename
                        }
                        __pdpDataProductVariant__: __type(name: "pdpDataProductVariant") {
                            possibleTypes {
                            name
                            }
                        }
                        __pdpDataProductMedia__: __type(name: "pdpDataProductMedia") {
                            possibleTypes {
                            name
                            }
                        }
                        __pdpDataUpcomingCampaign__: __type(name: "pdpDataUpcomingCampaign") {
                            possibleTypes {
                            name
                            }
                        }
                        __pdpDataProductContent__: __type(name: "pdpDataProductContent") {
                            possibleTypes {
                            name
                            }
                        }
                        __pdpDataCustomInfo__: __type(name: "pdpDataCustomInfo") {
                            possibleTypes {
                            name
                            }
                        }
                        __pdpDataProductInfo__: __type(name: "pdpDataProductInfo") {
                            possibleTypes {
                            name
                            }
                        }
                        __pdpDataInfo__: __type(name: "pdpDataInfo") {
                            possibleTypes {
                            name
                            }
                        }
                        __pdpDataSocialProof__: __type(name: "pdpDataSocialProof") {
                            possibleTypes {
                            name
                            }
                        }
                        __pdpDataProductDetail__: __type(name: "pdpDataProductDetail") {
                            possibleTypes {
                            name
                            }
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
                $shopName = '';
                $productName = '';

                $url = parse_url($this->parameters->get('url'));
                if (isset($url['path'])) {
                    $path = trim($url['path'], '/');
                    $expPath = explode('/', $path);

                    $shopName = $expPath[0];
                    $productName = $expPath[1];
                }

                $f = fopen($this->parameters->get('cacheDir').DIRECTORY_SEPARATOR.'product-detail-'.$shopName.'-'.$productName.'-request.html', 'w');
                fwrite($f, json_encode($response));
                fclose($f);
            } catch (\Exception $e) {}
        }

        if ($response) {
            $this->response = new \BI\Tokopedia\Message\Product\DetailResponse($this, $response);
        }

        return $this;
    }
}
