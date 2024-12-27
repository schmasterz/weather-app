<?php
declare(strict_types=1);
namespace App\Service;

use App\Constant\WeatherApiConstants;
use App\Interface\WeatherInterface;
use App\Repository\WeatherDataRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WeatherReportService implements WeatherInterface
{
    private WeatherService $weatherService;
    private array $weatherData;

    private array $fetchDays = [];


    private WeatherDataRepository $weatherDataRepository;

    private Request $request;

    /**
     * @throws TransportExceptionInterface
     * @throws InvalidArgumentException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function __construct(
            WeatherService $weatherService,
            RequestStack $request,
            WeatherDataRepository $weatherDataRepository
        )
    {
        $this->weatherService = $weatherService;
        $this->weatherData = $this->weatherService->getData();
        $this->request = $request->getCurrentRequest();
        $this->weatherDataRepository = $weatherDataRepository;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws InvalidArgumentException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \DateMalformedStringException
     * @throws \DateMalformedPeriodStringException
     */
    public function getData() : array
    {
        return [
                'temperature' => round($this->weatherData['main']['temp']),
                'icon' => $this->weatherService->getImgUrl() . $this->weatherData['weather'][0]['icon'] . '@4x.png',
                'main' => $this->weatherData['weather'][0]['main'],
                'trend' => round($this->getTemperatureTrend())
        ];
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function fetchData(): array
    {
        $weatherDataArray = [];
        foreach ($this->fetchDays as $date) {
            $response = $this->weatherService->getClient()->request(
                'GET',
                $this->weatherService->getUrl() . WeatherApiConstants::DAILY_WEATHER_ENDPOINT,
                [
                    'query' => [
                        'lat' => $this->weatherData['coord']['lat'],
                        'lon' => $this->weatherData['coord']['lon'],
                        'date' => $date,
                        'appid' => $this->weatherService->getKey(),
                        'units' => 'metric',
                    ]
                ]
            );
            $data = $response->toArray();
            $averageTemp =  ($data['temperature']['min'] + $data['temperature']['max']) / 2;

            $weatherDataArray[$date] = round($averageTemp, 2);
        }
        return $weatherDataArray;
    }


    /**
     * @throws TransportExceptionInterface
     * @throws \DateMalformedStringException
     * @throws \DateMalformedPeriodStringException
     */
    protected function getTemperatureTrend(): float
    {
        $city = $this->request->get('city');
        $this->getFetchDays();
       if(count($this->fetchDays) > 0) {
           $weatherData = $this->fetchData();
           $this->weatherDataRepository->saveWeatherData(
               $weatherData,
               $city,
               $this->weatherData['coord']['lat'],
               $this->weatherData['coord']['lon']
           );
       }
        return $this->weatherData['main']['temp']  - $this->weatherDataRepository->calculateAverageTemperature($city);
    }

    /**
     * @throws \DateMalformedStringException
     * @throws \DateMalformedPeriodStringException
     */
    private function getFetchDays(): void
    {
        $formattedDate = (new DateTime())->modify('-2 day');
        $latestDate = $this->weatherDataRepository->findLatestDate($this->request->get('city'));

        $startDate = null;
        $endDate = null;

        if ($latestDate === null || $latestDate < (new DateTime())->modify('-11 days')) {
            $startDate = (clone $formattedDate)->modify('-9 days');
            $endDate = (new DateTime())->modify('-1 day');
        } else if ($latestDate < $formattedDate) {
            $startDate = new DateTime($latestDate->format('Y-m-d'));
            $endDate = $formattedDate;
        }
        if ($startDate !== null && $endDate !== null) {
            $period = new DatePeriod(
                $startDate,
                new DateInterval('P1D'),
                $endDate->add(new DateInterval('P1D'))
            );

            foreach ($period as $date) {
                $this->fetchDays[] = $date->format('Y-m-d');
            }
        }
    }
}
