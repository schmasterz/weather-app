<?php
declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\WeatherDataRepository;
use App\Entity\WeatherData;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class WeatherDataRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private WeatherDataRepository $weatherDataRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->weatherDataRepository = $container->get(WeatherDataRepository::class);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function testCalculateAverageTemperatureForLast10Days(): void
    {
        $startDate = new \DateTime('2024-12-09');
        $startDate = new \DateTime('2024-12-09');
        for ($i = 0; $i < 10; $i++) {
            $date = (clone $startDate)->modify("+{$i} days");

            $weatherData = new WeatherData();
            $weatherData->setCity('Sofia');
            $weatherData->setTemperature(5.0 + rand(0, 2));
            $weatherData->setDate($date);
            $weatherData->setLat(42.6975);
            $weatherData->setLon(23.3242);
            $this->entityManager->persist($weatherData);
        }
        $this->entityManager->flush();

        $averageTemperature = $this->weatherDataRepository->calculateAverageTemperature('Sofia');

        $this->assertGreaterThanOrEqual(5.0, $averageTemperature);
        $this->assertLessThanOrEqual(7.0, $averageTemperature);
    }

    protected function tearDown(): void
    {
        $this->entityManager->clear();
    }

}
