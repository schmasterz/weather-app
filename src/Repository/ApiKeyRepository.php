<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\ApiKey;
use App\Exception\ApiKeyException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ApiKeyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiKey::class);
    }

    /**
     * @param string $encodedApiKey
     * @param string $name
     * @return ApiKey
     * @throws ApiKeyException
     */
    public function save(string $encodedApiKey, string $name): ApiKey
    {
        $existingApiKey = $this->findOneBy(['name' => $name]);
        if ($existingApiKey) {
            throw new ApiKeyException("An API key with the name '$name' already exists.");
        }

        $apiKey = new ApiKey();
        $apiKey->setName($name)->setApiKeyValue($encodedApiKey);
        $this->getEntityManager()->persist($apiKey);
        $this->getEntityManager()->flush();
        return $apiKey;
    }

    /**
     * @param int $id
     * @return bool
     * @throws ApiKeyException
     */
    public function deleteById(int $id): bool
    {
        $apiKeyEntity = $this->find($id);
        if (!$apiKeyEntity) {
            throw new ApiKeyException("An API key doesn't exists.");
        }

        $this->getEntityManager()->remove($apiKeyEntity);
        $this->getEntityManager()->flush();
        return true;
    }

    /**
     * @param $apiKey
     * @return bool
     */
    public function checkApiKey($keyValue) : bool
    {
        return (bool)$this->findOneBy(['apiKeyValue' => $keyValue]);
    }
}
