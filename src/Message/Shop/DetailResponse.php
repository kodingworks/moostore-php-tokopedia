<?php

namespace BI\Tokopedia\Message\Shop;

use BI\Entities\Shop;
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
                return new Shop([
                    'id' => isset($data['id']) ? $data['id'] : '',
                    'link' => isset($data['link']) ? $data['link'] : '',
                    'name' => isset($data['name']) ? $data['name'] : '',
                    'description' => isset($data['description']) ? $data['description'] : '',
                    'address' => isset($data['address']) ? $data['address'] : '',
                    'picture' => isset($data['picture']) ? $data['picture'] : '',
                    'rate' => isset($data['rate']) ? $data['rate'] : '',
                ]);
            }
        }

        return [];
    }

    public function parseData($response)
    {
        $data = [];

        if (isset($this->data[0]) && isset($this->data[0]->data) && isset($this->data[0]->data->shopInfo)) {
            $shopData = $this->data[0]->data->shopInfo;

            if (! $shopData->error->message) {
                $data['id'] = '';
                $data['link'] = '';
                $data['name'] = '';
                $data['description'] = '';
                $data['address'] = '';
                $data['picture'] = '';
                $data['rate'] = '';

                if ($shopData->result[0]) {
                    if ($shopData->result[0]->shopCore) {
                        $shopCore = $shopData->result[0]->shopCore;
                        if ($shopCore->shopID) {
                            $data['id'] = $shopCore->shopID;
                        }

                        if ($shopCore->url) {
                            $data['link'] = $shopCore->url;
                        }

                        if ($shopCore->name) {
                            $data['name'] = $shopCore->name;
                        }

                        if ($shopCore->description) {
                            $data['description'] = $shopCore->description;
                        }

                        if ($shopCore->description) {
                            $data['description'] = $shopCore->description;
                        }
                    }

                    if ($shopData->result[0]->location) {
                        $data['address'] = $shopData->result[0]->location;
                    }

                    if ($shopData->result[0]->shopAssets) {
                        $data['picture'] = [
                            $shopData->result[0]->shopAssets->cover,
                        ];
                    }

                    if ($shopData->result[0]->favoriteData->totalFavorite) {
                        $data['rate'] = $shopData->result[0]->favoriteData->totalFavorite;
                    }
                }
            }
        }

        return $data;
    }
}
