<?php

namespace App\Services;

use App\DTO\Location;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    public function __construct(
        private HttpClientInterface $client,
        #[Autowire('%weather_api_url%')]
        private string $apiUrl,
        #[Autowire('%env(API_WEATHER_KEY)%')]
        private readonly string $apiKey,
    ) {}

    public function getWeather(Location $location): ?array
    {
        try {
            $response = $this->client->request('GET', $this->apiUrl, [
                'query' => [
                    'lat' => $location->latitude,
                    'lon' => $location->longitude,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                    'lang' => 'fr',
                ],
            ]);

            return $response->toArray();
        } catch (\Exception $e) {
            return null;
        } 
    }
}