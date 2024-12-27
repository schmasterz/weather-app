<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\WeatherReportService;
use App\Service\WeatherService;
use App\Repository\WeatherDataRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\ResponseInterface;
use ReflectionClass;

class WeatherReportServiceTest extends KernelTestCase
{
    private WeatherReportService $weatherReportService;
    private MockObject $weatherServiceMock;
    private MockObject $requestStackMock;
    private MockObject $weatherDataRepositoryMock;
    private array $weatherData;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->weatherServiceMock = $this->createMock(WeatherService::class);

        $this->weatherDataRepositoryMock = $this->createMock(WeatherDataRepository::class);

        $this->requestStackMock = $this->createMock(RequestStack::class);
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('get')
            ->willReturn('Sofia');  // Simulate the 'city' query parameter
        $this->requestStackMock->method('getCurrentRequest')
            ->willReturn($requestMock);

        $this->weatherReportService = new WeatherReportService(
            $this->weatherServiceMock,
            $this->requestStackMock,
            $this->weatherDataRepositoryMock
        );
    }

    public function testFetchData(): void
    {
        $mockApiResponse = [
            'temperature' => [
                'min' => 5.0,
                'max' => 10.0
            ]
        ];

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('toArray')
            ->willReturn($mockApiResponse);

        $httpClientMock = $this->createMock(\Symfony\Contracts\HttpClient\HttpClientInterface::class);
        $httpClientMock->method('request')
            ->willReturn($responseMock);

        $this->weatherServiceMock->method('getClient')
            ->willReturn($httpClientMock);

        $this->weatherData = [
            'coord' => [
                'lat' => 42.6977,
                'lon' => 23.3219
            ],
            'main' => [
                'temp' => 8.0
            ],
            'weather' => [
                [
                    'icon' => '10d',
                    'main' => 'Rain'
                ]
            ]
        ];

        $reflection = new ReflectionClass(WeatherReportService::class);

        $fetchWeatherDataProperty =$reflection->getProperty('weatherData');
        $fetchWeatherDataProperty->setAccessible(true);
        $fetchWeatherDataProperty->setValue($this->weatherReportService, $this->weatherData);
        $fetchDaysProperty = $reflection->getProperty('fetchDays');
        $fetchDaysProperty->setAccessible(true); // Make private property accessible
        $fetchDaysProperty->setValue($this->weatherReportService, ['2024-12-01', '2024-12-02']);

        $result = $this->weatherReportService->fetchData();

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('2024-12-01', $result);
        $this->assertArrayHasKey('2024-12-02', $result);
        $this->assertEquals(7.5, $result['2024-12-01']);
        $this->assertEquals(7.5, $result['2024-12-02']);
    }

    public function testGetData(): void
    {
        $this->weatherData = [
            'main' => [
                'temp' => 20.5
            ],
            'weather' => [
                [
                    'icon' => '10d',
                    'main' => 'Rain'
                ]
            ]
        ];

        $weatherReportServiceMock = $this->getMockBuilder(WeatherReportService::class)
            ->setConstructorArgs([
                $this->weatherServiceMock,
                $this->requestStackMock,
                $this->weatherDataRepositoryMock
            ])
            ->onlyMethods(['getTemperatureTrend'])
            ->getMock();

        $weatherReportServiceMock->method('getTemperatureTrend')
            ->willReturn(2.5);

        $reflection = new \ReflectionClass(WeatherReportService::class);
        $weatherDataProperty = $reflection->getProperty('weatherData');
        $weatherDataProperty->setAccessible(true);
        $weatherDataProperty->setValue($weatherReportServiceMock, $this->weatherData);

        $this->weatherServiceMock->method('getImgUrl')
            ->willReturn('http://openweathermap.org/img/wn/');

        $result = $weatherReportServiceMock->getData();

        $this->assertEquals(21, $result['temperature']);
        $this->assertEquals('http://openweathermap.org/img/wn/10d@4x.png', $result['icon']);
        $this->assertEquals('Rain', $result['main']);
        $this->assertEquals(3, $result['trend']);
    }


}
