<?php

namespace App\DTO;

use Symfony\Component\Serializer\Attribute\Groups;

class Weather
{
    public function __construct(
        #[Groups(['getWeather', 'getWeatherFull'])]
        private readonly float $temperature,
        #[Groups(['getWeather', 'getWeatherFull'])]
        private readonly string $description,
        #[Groups(['getWeather', 'getWeatherFull'])]
        private readonly int $humidity,
        #[Groups(['getWeatherFull'])]
        private readonly ?float $feelsLike = null,
         #[Groups(['getWeatherFull'])]
        private readonly ?float $tempMin = null,
         #[Groups(['getWeatherFull'])]
        private readonly ?float $tempMax = null,
         #[Groups(['getWeatherFull'])]
        private readonly ?float $windSpeed = null,
         #[Groups(['getWeatherFull'])]
        private readonly ?string $city = null,
        private readonly ?string $icon = null
    ) {}

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function getFeelsLike(): ?float
    {
        return $this->feelsLike;
    }

    public function getTempMin(): ?float
    {
        return $this->tempMin;
    }

    public function getTempMax(): ?float
    {
        return $this->tempMax;
    }

    public function getWindSpeed(): ?float
    {
        return $this->windSpeed;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getHumidity(): int
    {
        return $this->humidity;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }
}