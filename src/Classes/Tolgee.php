<?php

namespace LaravelTolgee\Classes;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class Tolgee
{
    
    
    public static function request($endpoint, $data = [], $query = [], $method = 'get'){
        $response = Http::withHeaders(['X-API-Key' => Config::get('tolgee.api_key')])
                    ->withUrlParameters(['project' => Config::get('tolgee.project_id')])
                    ->baseUrl(Config::get('tolgee.host'))
                    ->withQueryParameters($query)
                    ->$method($endpoint, $data);
        
        return $response;
    }
}
