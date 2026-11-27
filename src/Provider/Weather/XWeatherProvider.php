<?php

namespace App\Provider\Weather;

use App\DTO\Location;
use App\DTO\Weather;
use App\Interface\WeatherProviderInterface;
use App\Mapper\Weather\XWeatherMapping;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class XWeatherProvider implements WeatherProviderInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        #[Autowire('%xweather_api_url%')]
        private readonly string $apiUrl,
        #[Autowire('%env(XWEATHER_CLIENT_ID)%')]
        private readonly string $clientId,
        #[Autowire('%env(XWEATHER_CLIENT_SECRET)%')]
        private readonly string $clientSecret,
        private readonly XWeatherMapping $mapping,
    ) {
    }

    public function getWeather(Location $location): Weather
    {
        $response = $this->client->request(
            'GET',
            sprintf(
                '%s/%s,%s',
                $this->apiUrl,
                $location->getLatitude(),
                $location->getLongitude()
            ),
            [
                'query' => [
                    'lang' => 'fr',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ],
            ]
        );

        $data = $response->toArray();

        if (
            !($data['success'] ?? false)
            || empty($data['response'][0]['periods'][0])
        ) {
            throw new \RuntimeException('Impossible de récupérer les données météo.');
        }

        return $this->mapping->map($data);
    }
}