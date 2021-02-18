<?php

namespace BI\Tokopedia;

use BI\AbstractGateway;
use BI\Message\RequestInterface;
use Psr\Http\Client\ClientInterface;

class Product extends AbstractGateway
{
    public function __construct(ClientInterface $httpClient = null, RequestInterface $httpRequest = null)
    {
        $this->httpClient = $httpClient;
        $this->httpRequest = $httpRequest;
    }

    public function __get($property)
    {
        # code...
    }

    public function getName()
    {
        return 'BI - Tokopedia Geodirectory Service';
    }

    public function getModuleName()
    {
        return 'bi.service.scrapper.tokopedia.geodirectory';
    }

    public function getFromURL(string $url)
    {
        $parameters['url'] = $url;

        // Fetch landing page
        return $this->createRequest(\BI\Tokopedia\Message\Product\DetailRequest::class, $parameters);
    }

    public function getFromShop($parameters = [])
    {
        return $this->createRequest(\BI\Tokopedia\Message\Product\ListOnShopRequest::class, $parameters);
    }

    public function getFromShopId(int $shopId)
    {
        $parameters = [
            'shopId' => $shopId,
        ];

        return $this->getFromShop($parameters);
    }
}