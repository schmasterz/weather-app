<?php
declare(strict_types=1);
namespace App\Service;

use App\Entity\ApiKey;
use App\Exception\ApiKeyException;
use App\Repository\ApiKeyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;

class ApiKeyService
{
    private EntityManagerInterface $entityManager;
    private ApiKeyRepository $apiKeyRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ApiKeyRepository $apiKeyRepository,
    ) {
        $this->entityManager = $entityManager;
        $this->apiKeyRepository = $apiKeyRepository;
    }

    /**
     * @param string $name
     * @return ApiKey
     * @throws ApiKeyException
     */
    public function generateAndSaveApiKey(string $name): ApiKey
    {
        return $this->apiKeyRepository->save($this->generateApiKeyValue(), $name);
    }
    private function generateApiKeyValue(): string
    {
        $apiKeyValue = uniqid("", true);
        return (new PasswordHasherFactory(['common' => ['algorithm' => 'bcrypt']]))
            ->getPasswordHasher('common')
            ->hash($apiKeyValue);
    }
    /**
     * @param int $id
     * @return bool True if the key was deleted, false otherwise
     * @throws ApiKeyException
     */
    public function deleteApiKey(int $id): bool
    {
        $this->apiKeyRepository->deleteById($id);
        return true;


    }


    /**
     * @return array
     */
    public function getAllApiKeys(): array
    {
        return $this->entityManager->getRepository(ApiKey::class)->findAll();
    }

    /**
     * @param $apiKey
     * @return bool
     */
    public function verifyApiKey($apiKey) : bool
    {
        return $this->apiKeyRepository->checkApiKey($apiKey);
    }
}
