<?php

namespace App\DTO;

class Location
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
    ) {}
}