<?php

namespace App\Services;

use App\DTO\Location;
use App\Interface\LocationProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class GeocodingLocationService
{
    public function __construct(
        private readonly LocationProviderInterface $locationProvider,
    ) {}
    
    public function getGpsLocation(string $postCode): ?Location
    {
        try {
            return $this->locationProvider->getLocation($postCode);

        } catch (ClientExceptionInterface $e) {
            return null;
        }
    }
}
