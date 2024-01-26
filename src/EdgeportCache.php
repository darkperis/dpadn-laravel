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
            $ret = $res->getBody()->getContents();

            if($ret && isset($ret['success']) && $ret['success'] == true) {
                if(isset($ret['message']))
                    \Log::info($ret['message']);
                else 
                    \Log::info('Purging started');
            }
            else {
                \Log::error('Could not purge the cache this time.');
            }
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
