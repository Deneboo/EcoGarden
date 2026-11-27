<?php

namespace App\Mapper\Weather;

use App\DTO\Weather;

class XWeatherMapping
{
    public function map(array $data): Weather
    {
        $response = $data['response'][0];
        $period = $response['periods'][0];

        return new Weather(
            city: $response['place']['name'],
            temperature: $period['tempC'],
            feelsLike: $period['feelslikeC'],
            humidity: $period['humidity'],
            description: $period['weather'],
            icon: $period['icon'],
            windSpeed: $period['windSpeedKPH'],
            tempMin: null,
            tempMax: null,
        );
    }
}