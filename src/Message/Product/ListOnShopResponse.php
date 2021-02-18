<?php

namespace BI\Tokopedia\Message\Product;

use BI\Entities\Price;
use BI\Entities\Product;
use BI\Message\AbstractResponse;

class ListOnShopResponse extends AbstractResponse
{
    public function getData()
    {
        if ($this->data->response) {
            $metadata = $this->data->metadata;
            $this->data = $this->data->response;

            if (is_string($this->data)) {
                $this->data = json_decode($this->data);
            }

            $data = $this->parseData($this->data);

            if ($data) {
                $products = [];
                foreach ($data->data as $item) {
                    $price = isset($item['price']) ? $item['price'] : [];

                    $products[] = new Product([
                        'link' => isset($item['link']) ? $item['link'] : '',
                        'name' => isset($item['name']) ? $item['name'] : '',
                        'description' => isset($item['description']) ? $item['description'] : '',
                        'category' => isset($item['category']) ? $item['category'] : '',
                        'weight' => isset($item['weight']) ? $item['weight'] : '',
                        'condition' => isset($item['condition']) ? $item['condition'] : '',
                        'picture' => isset($item['picture']) ? $item['picture'] : '',
                        'stock' => isset($item['stock']) ? $item['stock'] : '',
                        'price' => new Price($price),
                        'rate' => isset($item['rate']) ? $item['rate'] : '',
                    ]);
                }

                return [
                    'currentPage' => $metadata->page,
                    'totalPage' => ceil(($data->totalItem - 1) / $metadata->limit),
                    'totalItem' => $data->totalItem,
                    'data' => $products
                ];
            }
        }

        return [];
    }

    public function parseData($response)
    {
        $data = [];
        $totalItem = 0;

        if (isset($this->data[0]) && isset($this->data[0]->data) && isset($this->data[0]->data->productList)) {
            $itemData['link'] = '';
            $itemData['name'] = '';
            $itemData['description'] = '';
            $itemData['category'] = '';
            $itemData['weight'] = '';
            $itemData['condition'] = '';
            $itemData['picture'] = '';
            $itemData['stock'] = '';
            $itemData['price'] = '';
            $itemData['rate'] = '';

            $totalItem = $this->data[0]->data->productList->totalData;
            $products = $this->data[0]->data->productList->data;

            foreach ($products as $product) {
                if ($product->product_url) {
                    $itemData['link'] = $product->product_url;
                }

                if ($product->name) {
                    $itemData['name'] = $product->name;
                }

                if ($product->primary_image) {
                    $itemData['picture'] = [
                        $product->primary_image->thumbnail,
                    ];
                }

                if ($product->price) {
                    $price = str_replace(['Rp', '.', ' '], '', $product->price->text_idr);
                    $itemData['price'] = [
                        'amount' => $price,
                        'discount' => 0,
                        'tax' => 0,
                        'otherCharge' => 0,
                    ];
                }

                if ($product->campaign && $product->campaign->original_price_fmt) {
                    $originalPrice = $product->campaign->original_price_fmt;
                    $originalPrice = str_replace(['Rp', ' ', '.'], '', $originalPrice);

                    if ($originalPrice > 0) {
                        $discountedPrice = $itemData['price']['amount'];

                        // if ($itemData['name'] == 'Natur-E White Trubright - 3 [ TRUBRIGHT3 ]') {
                        //     var_dump($itemData); exit;
                        // }
                        $itemData['price']['amount'] = $originalPrice;
                        $itemData['price']['discount'] = (int) $originalPrice - (int) $discountedPrice;
                    }
                }

                if ($product->stats) {
                    $itemData['rate'] = $product->stats->rating;
                }

                $data[] = $itemData;
            }
        }

        return (object)[
            'totalItem' => $totalItem,
            'data' => $data
        ];
    }
}
