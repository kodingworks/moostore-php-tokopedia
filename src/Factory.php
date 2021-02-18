<?php

namespace BI\Tokopedia;

use BI\AbstractGateway;
use GuzzleHttp\Cookie\FileCookieJar;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class Factory extends AbstractGateway
{
    public function __construct(ClientInterface $httpClient = null, RequestInterface $httpRequest = null)
    {
        parent::__construct($httpClient, $httpRequest);
        $this->setupCookieJar();
        $this->setupCache();
    }

    public function __get($property)
    {
        $this->setupCookieJar();
        $this->setupCache();

        $className = sprintf('%1s\%2s', 'BI\Tokopedia', ucfirst(strtolower($property)));
        if (class_exists($className)) {
            $class  = new $className($this->httpClient, $this->httpRequest);
            $class->initialize($this->parameters->all());

            return $class;
        }

        throw new \BI\Exception\ClassNotFoundException;
    }

    public function getName()
    {
        return 'BI - Tokopedia';
    }

    public function getModuleName()
    {
        return 'bi.scrapper.tokopedia';
    }

    public function setupCookieJar()
    {
        if (! $this->parameters->get('cookieJar')) {
            $cookieDir = $this->parameters->get('cookieDir', __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
            $cookieFile = $this->parameters->get('cookieFile', 'cookie-jar');

            $cookieJar = $cookieDir.$cookieFile;

            if (file_exists($cookieJar)) {
                @unlink($cookieJar);
            }

            if (! file_exists($cookieJar)) {
                @touch($cookieJar);
            }

            $this->parameters->set('cookieJar', new FileCookieJar($cookieJar, true));
        }
    }

    public function setupCache()
    {
        $cacheDir = $this->parameters->get('cacheDir');
        if (! $cacheDir) {
            $cacheDir = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache';
        }

        if (! is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }

        $this->parameters->set('cacheDir', $cacheDir);
    }
}