<?php

namespace App\Controller;

use App\DTO\Weather;
use App\Services\GeocodingLocationService;
use App\Services\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    public function __construct(
    private GeocodingLocationService $geocodingLocation,
    private WeatherService $weatherService
    ) {}

    #[Route('/api/weather', name: 'app_weather', methods: ['GET'])]
    public function index(): ?JsonResponse
    {
        $user = $this->getUser();
        $postCode = $user->getPostCode();

        if (empty($postCode)) {
            return null;
        }
        $location = $this->geocodingLocation->getGpsLocation($postCode);
        $weather = $this->weatherService->getWeather($location);

        return $this->json($weather, JsonResponse::HTTP_OK, [], ['groups' => ['getWeather']]);
    }

    #[Route('/api/weather/full', name: 'app_weather_full', methods: ['GET'])]
    public function full(): ?JsonResponse
    {
        $user = $this->getUser();
        $postCode = $user->getPostCode();

        if (empty($postCode)) {
            return null;
        }
        $location = $this->geocodingLocation->getGpsLocation($postCode);
        $weather = $this->weatherService->getWeather($location);

        return $this->json($weather, JsonResponse::HTTP_OK, [], ['groups' => ['getWeatherFull']]);
    }
}
