<?php

namespace App\Provider;

use App\DTO\Location;
use App\DTO\Weather;
use App\Interface\WeatherProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenWeatherMapProvider implements WeatherProviderInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        #[Autowire('%env(API_WEATHER_KEY)%')]
        private readonly string $apiKey,
        #[Autowire('%weather_api_url%')]
        private readonly string $apiUrl,
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

        return new Weather(
            city: $data['name'],
            temperature: $data['main']['temp'],
            feelsLike: $data['main']['feels_like'],
            humidity: $data['main']['humidity'],
            description: $data['weather'][0]['description'],
            icon: $data['weather'][0]['icon'],
            windSpeed: $data['wind']['speed'],
            tempMin: $data['main']['temp_min'],
            tempMax: $data['main']['temp_max'],
        );
    }
}
