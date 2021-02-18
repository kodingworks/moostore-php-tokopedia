<?php

namespace BI\Tokopedia\Message;

use BI\Message\AbstractRequest;
use BI\Message\RequestInterface;
use Psr\Http\Client\ClientInterface;

class LandingRequest extends AbstractRequest
{
    protected $endpoint = 'https://m.tokopedia.com';

    protected $method = 'GET';

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
                CURLOPT_FOLLOWLOCATION => 1,
            ],
        ]);

        return $options;
    }

    public function getHeaders()
    {
        return [
            'User-Agent' => 'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Mobile Safari/537.36',
            'Connection' => 'keep-alive',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept' => '*/*',
            'Cache-Control' => 'no-cache',
        ];
    }

    public function getData()
    {
        return [];
    }

    public function createResponse($response)
    {
        if (! $this->parameters->get('no-cache', false)) {
            try {
                $f = fopen($this->parameters->get('cacheDir').DIRECTORY_SEPARATOR.'landing-request.html', 'w');
                fwrite($f, $response);
                fclose($f);
            } catch (\Exception $e) {
            }
        }

        $this->response = $response;

        return $this;
    }
}
