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

        return $this->json([
            'city' => $weather->getCity(),
            'temperature' => $weather->getTemperature(),
            'feelsLike' => $weather->getFeelsLike(),
            'tempMin' => $weather->getTempMin(),
            'tempMax' => $weather->getTempMax(),
            'windSpeed' => $weather->getWindSpeed(),
            'description' => $weather->getDescription(),
            'humidity' => $weather->getHumidity(),
            'icon' => $weather->getIcon(),
        ]);
    }
}
