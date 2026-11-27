<?php

namespace App\Interface;

use App\DTO\Location;
use App\DTO\Weather;

interface WeatherProviderInterface
{
    public function getWeather(Location $location): Weather;
}