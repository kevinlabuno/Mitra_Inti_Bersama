<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait RequestService
{
    public function request($method, $requestUrl, $formParams = [], $headers = [])
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
            'connect_timeout' => 50,
            'timeout' => 50
        ]);
        
        if (request()->header('authorization') !== null) {
            $headers['authorization'] = request()->header('authorization');
        }

        return $client->request($method, $requestUrl,
            [
                'form_params' => $formParams,
                'headers' => $headers
            ]
        );
    }


    public function requestMultipart($method, $requestUrl, $params = [], $headers = [])
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
            'connect_timeout' => 10,
            'timeout' => 10
        ]);

        if (request()->header('authorization') !== null) {
            $headers['authorization'] = request()->header('authorization');
        }

        return $client->request($method, $requestUrl,
            [
                'multipart' => $params,
                'headers' => $headers
            ]
        );
    }
}
