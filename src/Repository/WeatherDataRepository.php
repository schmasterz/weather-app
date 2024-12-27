<?php
declare(strict_types=1);
namespace App\Repository;

use App\Entity\WeatherData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WeatherDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeatherData::class);
    }

    public function findLatestDate(string $city): ?\DateTimeInterface
    {
        $result = $this->createQueryBuilder('w')
            ->select('w.date')
            ->where('w.city = :city')
            ->setParameter('city', $city)
            ->orderBy('w.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
           ->getOneOrNullResult();
        return $result ? $result['date'] : null;
    }

    public function saveWeatherData(array $weatherDataArray, string $city, float $lat, float $lon): void
    {
        foreach ($weatherDataArray as $date => $temperature) {
            $existingRecord = $this->findOneBy(['city' => $city, 'date' => new \DateTime($date)]);
            if ($existingRecord) {
                continue;
            }

            $weatherData = new WeatherData();
            $weatherData->setCity($city)
                ->setTemperature($temperature)
                ->setLat($lat)
                ->setLon($lon)
                ->setDate(new \DateTime($date));
            $this->getEntityManager()->persist($weatherData);
        }
        $this->getEntityManager()->flush();
    }

    public function calculateAverageTemperature(string $city): float
    {
        $endDate = (new \DateTime())->modify('-1 day');
        $startDate = (new \DateTime())->modify('-11 days'); // 9 days ago

        $result = $this->createQueryBuilder('w')
            ->select('AVG(w.temperature) AS avg_temperature')
            ->where('w.city = :city')
            ->andWhere('w.date BETWEEN :startDate AND :endDate')
            ->setParameter('city', $city)
            ->setParameter('startDate', $startDate->format('Y-m-d'))
            ->setParameter('endDate', $endDate->format('Y-m-d'))
            ->getQuery()
            ->getSingleScalarResult();
        return $result !== null ? (float) $result : 0.0;
    }
}
