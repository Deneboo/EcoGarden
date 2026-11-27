<?php

namespace App\Provider\Location;

use App\DTO\Location;
use App\Interface\LocationProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class OpenWeatherMapLocationProvider implements LocationProviderInterface
{
    public function __construct(
        private HttpClientInterface $client,
        #[Autowire('%open_weather_map_geo_api_url%')]
        private string $apiUrl,
        #[Autowire('%env(OPEN_WEATHER_MAP_API_KEY)%')]
        private readonly string $apiKey,
    ) {}
    
    public function getLocation(string $postCode): ?Location
    {
        if (empty($postCode)) {
            return null;
        }
        
        try {
            $response = $this->client->request('GET', $this->apiUrl, [
                'query' => [
                    'zip' => $postCode . ',FR',
                    'appid' => $this->apiKey,
                ],
            ]);

            $data = $response->toArray();

            return new Location(
                latitude: $data['lat'],
                longitude: $data['lon'],
            );

        } catch (ClientExceptionInterface $e) {
            return null;
        }
    }
}
