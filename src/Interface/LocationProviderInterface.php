<?php

namespace App\Interface;

use App\DTO\Location;

interface LocationProviderInterface
{
    public function getLocation(string $postcode): ?Location;
}