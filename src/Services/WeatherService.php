<?php

namespace App\Services;

use App\DTO\Location;
use App\DTO\Weather;
use App\Interface\WeatherProviderInterface;


class WeatherService
{
    public function __construct(
        private readonly WeatherProviderInterface $weatherProvider,
    ) {}

    public function getWeather(Location $location): ?Weather
    {
        try {
            return $this->weatherProvider->getWeather($location);
        } catch (\Exception $e) {
            return null;
        }
    }
}