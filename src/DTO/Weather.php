<?php

namespace App\DTO;

class Weather
{
    public function __construct(
        private readonly float $temperature,
        private readonly string $description,
        private readonly int $humidity,
        private readonly ?float $feelsLike = null,
        private readonly ?float $tempMin = null,
        private readonly ?float $tempMax = null,
        private readonly ?float $windSpeed = null,
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