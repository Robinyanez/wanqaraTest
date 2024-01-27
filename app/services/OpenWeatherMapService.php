<?php

namespace App\Services;

use App\Interfaces\HttpCodeInterface;
use GuzzleHttp\Client;
use App\Traits\Utilities\ApiResponse;

class OpenWeatherMapService implements HttpCodeInterface
{
    use ApiResponse;

    protected $apiKey;
    protected $client;

    public function __construct()
    {
        $this->apiKey = config('services.openweathermap.api_key');
        $this->client = new Client(['base_uri' => 'https://api.openweathermap.org/data/2.5/']);
    }

    public function getWeatherByCity($city)
    {
        $response = $this->client->get('weather', [
            'query' => [
                'q' => $city,
                'appid' => $this->apiKey,
            ],
        ]);

        return $this->successResponse($response->getBody()->getContents(), self::OK, 'success');
    }
}
