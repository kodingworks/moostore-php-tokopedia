<?php

namespace BI\Tokopedia;

use BI\AbstractGateway;
use BI\Message\RequestInterface;
use Psr\Http\Client\ClientInterface;

class Shop extends AbstractGateway
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
        return 'BI - Tokopedia Service';
    }

    public function getModuleName()
    {
        return 'bi.service.scrapper.Tokopedia.shop';
    }

    public function getDetail($parameters = [])
    {
        // Fetch landing page
        $landing = $this->createRequest(\BI\Tokopedia\Message\LandingRequest::class, $parameters)->send();

        return $this->createRequest(\BI\Tokopedia\Message\Shop\DetailRequest::class, $parameters);
    }
}