<?php

namespace App\DTO;

class Location
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
    ) {}

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }
}