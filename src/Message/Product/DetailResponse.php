<?php

namespace BI\Tokopedia\Message\Product;

use BI\Entities\Price;
use BI\Entities\Product;
use BI\Message\AbstractResponse;

class DetailResponse extends AbstractResponse
{
    public function getData()
    {
        if ($this->data) {
            if (is_string($this->data)) {
                $this->data = json_decode($this->data);
            }

            $data = $this->parseData($this->data);

            if ($data) {
                $price = isset($data['price']) ? $data['price'] : [];

                return new Product([
                    'link' => isset($data['link']) ? $data['link'] : '',
                    'name' => isset($data['name']) ? $data['name'] : '',
                    'description' => isset($data['description']) ? $data['description'] : '',
                    'category' => isset($data['category']) ? $data['category'] : '',
                    'weight' => isset($data['weight']) ? $data['weight'] : '',
                    'condition' => isset($data['condition']) ? $data['condition'] : '',
                    'picture' => isset($data['picture']) ? $data['picture'] : '',
                    'stock' => isset($data['stock']) ? $data['stock'] : '',
                    'price' => new Price($price),
                    'rate' => isset($data['rate']) ? $data['rate'] : '',
                ]);
            }
        }

        return [];
    }

    public function parseData($response)
    {
        $data = [];

        if (isset($this->data[0]) && isset($this->data[0]->data) && isset($this->data[0]->data->pdpGetLayout)) {
            $data['link'] = '';
            $data['name'] = '';
            $data['description'] = '';
            $data['category'] = '';
            $data['weight'] = '';
            $data['condition'] = '';
            $data['picture'] = '';
            $data['stock'] = '';
            $data['price'] = '';
            $data['rate'] = '';

            $productData = $this->data[0]->data->pdpGetLayout;
            if ($productData->basicInfo) {
                $basicInfo = $productData->basicInfo;

                if ($basicInfo->url) {
                    $data['link'] = $basicInfo->url;
                }

                if ($basicInfo->category) {
                    $data['category'] = $basicInfo->category->name;
                }

                if ($basicInfo->weight) {
                    $data['weight'] = $basicInfo->weight;
                }

                if ($basicInfo->condition) {
                    $data['condition'] = $basicInfo->condition;
                }

                if ($basicInfo->stats) {
                    $data['rate'] = $basicInfo->stats->rating;
                }
            }

            if ($productData->components) {
                foreach ($productData->components as $component) {
                    if ($component->name == 'product_media') {
                        $productMedia = $component->data[0]->media;

                        $data['picture'] = [];
                        foreach ($productMedia as $media) {
                            $data['picture'][] = $media->urlOriginal;
                        }
                    }

                    if ($component->name == 'product_content') {
                        $productContent = $component->data[0];

                        if ($productContent->name) {
                            $data['name'] = $productContent->name;
                        }

                        $price = [
                            'amount' => 0,
                            'discount' => 0,
                            'tax' => 0,
                            'otherCharge' => 0,
                        ];
                        if ($productContent->price) {
                            $price['amount'] = $productContent->price->value;
                        }
                        if ($productContent->campaign && $productContent->campaign->originalPrice > 0) {
                            $price['amount']  = $productContent->campaign->originalPrice;
                            $price['discount'] = $productContent->campaign->originalPrice - $productContent->price->value;
                        }
                        $data['price'] = $price;

                        if ($productContent->stock) {
                            $data['stock'] =$productContent->stock->value;
                        }
                    }

                    if ($component->name == 'product_info') {
                        foreach ($component->data as $productInfo) {
                            foreach ($productInfo->content as $key => $productInfoContent) {
                                if ($productInfoContent->title == 'Deskripsi') {
                                    $data['description'] = $productInfoContent->subtitle;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }
}
