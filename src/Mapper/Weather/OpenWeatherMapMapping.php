<?php

namespace App\Mapper\Weather;

use App\DTO\Weather;

class OpenWeatherMapMapping
{
    public function map(array $data): Weather
    {
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