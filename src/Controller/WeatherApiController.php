<?php

namespace App\Controller;

use App\Services\GeocodingLocationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\WeatherService;

final class WeatherApiController extends AbstractController
{
    public function __construct(
    private GeocodingLocationService $geocodingLocation,
    private WeatherService $weatherService
    ) {}

    #[Route('/api/weather', name: 'app_weather', methods: ['GET'])]
    public function index(): ?JsonResponse
    {
        // echo "Coucou";
        $user = $this->getUser();
        $postCode = $user->getPostCode();

        if (empty($postCode)) {
            return null;
        }
        $location = $this->geocodingLocation->getGpsLocation($postCode);
        $weather = $this->weatherService->getWeather($location);

        return new JsonResponse($weather);
    }
}
