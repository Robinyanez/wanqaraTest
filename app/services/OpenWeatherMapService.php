<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Traits\Utilities\ApiResponse;

class OpenWeatherMapService
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
        try {

            $response = $this->client->get('weather', [
                'query' => [
                    'q'     => $city,
                    'units' => 'metric',
                    'appid' => $this->apiKey,
                ],
            ]);

            if ($response->getStatusCode() != 200) {

                return $this->errorResponse(null, $response->getStatusCode());
            }

            return json_decode($response->getBody()->getContents());

        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(),$e->getCode());

        }
    }
}
