<?php

date_default_timezone_set('Asia/Jakarta');

use PHPUnit\Framework\TestCase;

use GuzzleHttp\Client;

class BasicTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->bi = new BI\BI;
        $this->biTokopedia = $this->bi->create(\BI\Tokopedia\Factory::class, new Client);

        $this->biTokopedia->setParameter('debug', true);
        $this->biTokopedia->setParameter('cacheDir', __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache');
    }

    public function testGetShopDetail()
    {
        $shop = $this->biTokopedia->shop->getDetail([
            'q' => 'jamkuid'
        ])->send();

        var_dump($shop->getResponse()->getData()); exit;
    }

    public function _testGetProductFromURL()
    {
        // $url = 'https://m.tokopedia.com/annsb/my-baby-minyak-telon-150ml?xClientId=1691471407.1606521987';
        $url = 'https://m.tokopedia.com/nature-official/natur-e-advanced-anti-aging-face-wash?xClientId=1691471407.1606521987';
        $detail = $this->biTokopedia->product->getFromURL($url)->send();

        var_dump($detail->getResponse()->getData()); exit;
    }

    public function _testGetProductListsFromShop()
    {
        // Get shop id from shop detail
        $shopID = 3075754;

        $products = $this->biTokopedia->product->getFromShopId($shopID)->send();

        var_dump($products->getResponse()->getData()); exit;
    }

    public function _testParse()
    {
        // Get shop detail
        // $filePath = file_get_contents(__DIR__.'/../cache/shop-detail-jamkuid-request.html');
        // $data = (new \BI\Tokopedia\Message\Shop\DetailResponse(new \BI\Tokopedia\Message\Shop\DetailRequest(new Client), $filePath))->getData();

        // Get product detail from url
        // $filePath = file_get_contents(__DIR__.'/../cache/product-detail-annsb-my-baby-minyak-telon-150ml-request.html');
        $filePath = file_get_contents(__DIR__.'/../cache/product-detail-nature-official-natur-e-advanced-anti-aging-face-wash-request.html');
        $data = (new \BI\Tokopedia\Message\Product\DetailResponse(new \BI\Tokopedia\Message\Product\DetailRequest(new Client), $filePath))->getData();

        var_dump($data); exit;
    }
}