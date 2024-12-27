<?php
namespace App\Tests\Service;

use App\Service\WeatherService;
use App\Constant\WeatherApiConstants;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\HttpClient\ResponseInterface;

class WeatherServiceTest extends KernelTestCase
{
    private MockObject $httpClient;
    private MockObject $requestStack;
    private MockObject $request;
    private WeatherService $weatherService;
    private FilesystemAdapter $cache;

    protected function setUp(): void
    {
        self::bootKernel();

        // Mock HttpClientInterface
        $this->httpClient = $this->createMock(HttpClientInterface::class);

        // Mock RequestStack and Request
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->request = $this->createMock(Request::class);
        $this->request->method('get')
            ->with('city')
            ->willReturn('Sofia');

        $this->requestStack->method('getCurrentRequest')
            ->willReturn($this->request);

        // Initialize WeatherService with the mocked dependencies
        $this->weatherService = new WeatherService(
            $this->httpClient,
            'api-key',
            'https://api.openweathermap.org/data/2.5/',
            'https://api.openweathermap.org/img/wn/',
            $this->requestStack
        );

        $this->cache = new FilesystemAdapter();
    }

    /**
     * Test when data is retrieved from the cache.
     *
     * @throws TransportExceptionInterface
     * @throws InvalidArgumentException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetDataFromCache(): void
    {
        // Simulate cached data
        $cacheItem = $this->cache->getItem('weather_Sofia');
        $cacheItem->set(['temp' => 10, 'humidity' => 80]);
        $this->cache->save($cacheItem);

        // Get the data from WeatherService (should come from cache)
        $data = $this->weatherService->getData();

        // Assert that the cached data is returned
        $this->assertEquals(['temp' => 10, 'humidity' => 80], $data);
    }

    /**
     * Test when data is fetched from the API (not cached).
     *
     * @throws TransportExceptionInterface
     * @throws InvalidArgumentException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetDataFromApiWhenNotCached(): void
    {
        // Create a mock response for the API call
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('toArray')
            ->willReturn(['temp' => 15, 'humidity' => 70]);

        // Mock the HTTP client's request method to return the mock response
        $this->httpClient->method('request')
            ->willReturn($mockResponse);

        // Explicitly clear the cache to force the API call
        $this->cache->clear(); // Ensure the cache is cleared so that it doesn't return any cached data

        // Get the data from WeatherService (should come from the API)
        $data = $this->weatherService->getData();

        // Assert that the mocked API data is returned
        $this->assertEquals(['temp' => 15, 'humidity' => 70], $data);
    }

    /**
     * Test fetchData method from WeatherService.
     *
     * @throws TransportExceptionInterface
     * @throws InvalidArgumentException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testFetchData(): void
    {
        // Create a mock response for the API call
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('toArray')
            ->willReturn(['temp' => 18, 'humidity' => 60]);

        // Mock the HTTP client's request method to return the mock response
        $this->httpClient->method('request')
            ->willReturn($mockResponse);

        // Get the data from the fetchData method
        $data = $this->weatherService->fetchData();

        // Assert that the mocked API data is returned
        $this->assertEquals(['temp' => 18, 'humidity' => 60], $data);
    }
}
