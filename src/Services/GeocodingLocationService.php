<?php

namespace App\Services;

use App\DTO\Location;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class GeocodingLocationService
{
    public function __construct(
        private HttpClientInterface $client,
        #[Autowire('%geo_api_url%')]
        private string $apiUrl,
        #[Autowire('%env(API_GEO_KEY)%')]
        private readonly string $apiKey,
    ) {}
    
    public function getGpsLocation(string $postCode): ?Location
    {


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
