<?php

namespace App\Provider\Location;

use App\DTO\Location;
use App\Interface\LocationProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeoApiGouvProvider implements LocationProviderInterface
{
    public function __construct(
        private HttpClientInterface $client,
        #[Autowire('%geo_api_gouv_url%')]
        private string $apiUrl
    ) {}
    
    public function getLocation(string $postCode): ?Location
    {
        if (empty($postCode)) {
            return null;
        }

        try {
            $response = $this->client->request('GET', $this->apiUrl, [
                'query' => [
                    'codePostal' => $postCode,
                    'fields' => 'nom,centre',
                ],
            ]);

            $data = $response->toArray();

            if (empty($data)) {
                return null;
            }

            // Assuming the API returns an array of communes, we take the first one
            $commune = $data[0];

            $coordinates = $commune['centre']['coordinates'];

            $longitude = $coordinates[0];
            $latitude = $coordinates[1];

            return new Location(
                latitude: $latitude,
                longitude: $longitude,
            );

        } catch (ClientExceptionInterface $e) {
            return null;
        }
    }
}