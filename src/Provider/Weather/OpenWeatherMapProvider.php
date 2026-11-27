<?php

namespace App\Provider\Weather;

use App\DTO\Location;
use App\DTO\Weather;
use App\Interface\WeatherProviderInterface;
use App\Mapper\Weather\OpenWeatherMapMapping;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenWeatherMapProvider implements WeatherProviderInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        #[Autowire('%env(OPEN_WEATHER_MAP_API_KEY)%')]
        private readonly string $apiKey,
        #[Autowire('%open_weather_map_api_url%')]
        private readonly string $apiUrl,
        private readonly OpenWeatherMapMapping $mapping,
    ) {
    }

    public function getWeather(Location $location): Weather
    {
        $response = $this->client->request(
            'GET',
            $this->apiUrl,
            [
                'query' => [
                    'lat' => $location->getLatitude(),
                    'lon' => $location->getLongitude(),
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                    'lang' => 'fr',
                ],
            ]
        );

        $data = $response->toArray();

        return $this->mapping->map($data);
    }
}
