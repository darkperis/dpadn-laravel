<?php

namespace Darkpony\ADNCache;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class EdgeportCache
{

    public function purge(string $items)
    {
        $endpoint    = config('adncache.endpoint', 'https://api.edgeport.com/api/v1/wordpress/purge');
        $api_key     = config('adncache.api_key', '');
        $baseurl     = env('APP_URL');

        if($api_key != '' && $endpoint != '') {

            if($items == '*') {
                $data = [
                    'purge' => 'all',
                    'url' => [
                        $baseurl
                    ]
                ];
            }
            elseif($items == '/') {

                $data = [
                    'purge' => 'bulk',
                    'url' => [$baseurl]
                ];
            }
            else {
                $data = [];
                $urls = explode(',', $items);
                foreach ($urls as $url) {
                    if($url == '/') {
                        $data[] = $baseurl;
                    }
                    else {
                        $data[] = $baseurl.$url;
                    }
                }

                $data = [
                    'purge' => 'bulk',
                    'url' => $data
                ];
            }
            
            $body = json_encode( $data );

            $options = [
                'body'    => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $api_key,
                ]
            ];

            $client = new Client();
            $res = $client->request('POST', $endpoint, $options);
            $response = $res->getBody()->getContents();
            $result = json_decode($response, true);

            if ( ! empty( $result['error'] ) ) {
                \Log::info('Edgeport: Error while trying to purge cache: ' . $result['error']['message']);
            }

            if ( ! $result['success'] ) {
                \Log::info('Edgeport: Unknown error while trying to purge cache.');
            }

            \Log::info('Edgeport: Cache purged successfully');

        }
    }

    public function purgeAll()
    {
        return $this->purge('*');
    }

    public function purgeItems(array $items)
    {
        if (count($items)) {
            return $this->purge(implode(',', $items));
        }
    }
}
