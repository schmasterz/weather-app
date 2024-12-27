<?php
declare(strict_types=1);
namespace App\Controller;

use App\Service\WeatherReportService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiWeatherController extends AbstractController
{
    private WeatherReportService $weatherReportService;

    public function __construct(WeatherReportService $weatherReportService)
    {
        $this->weatherReportService = $weatherReportService;
    }

    #[Route('/api/weather/{city}', name: '/api/weather', methods: ['GET'])]
    public function getWeather(string $city): JsonResponse
    {
        try {
            $weatherReportData = $this->weatherReportService->getData();
            return new JsonResponse(['city' => $city,] + $weatherReportData);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Could not retrieve weather data.'], 500);
        }
    }
}
