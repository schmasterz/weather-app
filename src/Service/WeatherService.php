<?php
declare(strict_types=1);
namespace App\Service;

use App\Constant\WeatherApiConstants;
use App\Interface\WeatherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class WeatherService implements WeatherInterface
{
    private HttpClientInterface $client;
    private string $apiUrl;
    private string $apiKey;

    private string $apiImgUrl;
    private FilesystemAdapter $cache;

    private string $city;

    private Request $request;

    private int $cacheExpiration = WeatherApiConstants::CURRENT_WEATHER_EXPIRATION_TIME;

    public function __construct(
        HttpClientInterface $client,
        string $apiKey,
        string $apiUrl,
        string $apiImgUrl,
        RequestStack $request
    )
    {
        $this->apiUrl = $apiUrl;
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->apiImgUrl = $apiImgUrl;
        $this->request = $request->getCurrentRequest();
        $this->cache = new FilesystemAdapter();
        $this->city = $this->request->get('city');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws InvalidArgumentException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getData(): array
    {
        $cacheItem = $this->cache->getItem('weather_' . $this->city);
        if ($cacheItem->isHit()) {
            $weatherData = $cacheItem->get();
        } else {
            $weatherData = $this->fetchData();
            $cacheItem->set($weatherData)->expiresAfter($this->cacheExpiration);
            $this->cache->save($cacheItem);
        }
        return $weatherData;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchData(): array
    {
        $response = $this->client->request('GET', $this->apiUrl . WeatherApiConstants::CURRENT_WEATHER_ENDPOINT, [
            'query' => [
                'q' => $this->city,
                'appid' => $this->apiKey,
                'units' => 'metric',
            ]
        ]);

        return $response->toArray();
    }

    public function getImgUrl(): string
    {
        return $this->apiImgUrl;
    }
    public function getClient(): HttpClientInterface
    {
        return $this->client;
    }

    public function getUrl() : string
    {
        return $this->apiUrl;
    }

    public function getKey() : string
    {
        return $this->apiKey;
    }
}
