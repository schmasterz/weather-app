<?php
declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\ApiKey;
use App\Exception\ApiKeyException;
use App\Repository\ApiKeyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ApiKeyRepositoryTest extends KernelTestCase
{
    private ApiKeyRepository $apiKeyRepository;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->apiKeyRepository = self::getContainer()->get(ApiKeyRepository::class);
    }

    public function testSaveCreatesApiKeyWhenNameDoesNotExist(): void
    {
        $encodedApiKey = 'encodedApiKey123';
        $name = 'newApiKey';

        $existingApiKey = $this->apiKeyRepository->findOneBy(['name' => $name]);
        $this->assertNull($existingApiKey);

        $apiKey = $this->apiKeyRepository->save($encodedApiKey, $name);

        $this->assertInstanceOf(ApiKey::class, $apiKey);
        $this->assertEquals($name, $apiKey->getName());

        $persistedApiKey = $this->apiKeyRepository->findOneBy(['name' => $name]);
        $this->assertNotNull($persistedApiKey);
        $this->assertEquals($encodedApiKey, $persistedApiKey->getApiKeyValue());
    }

    public function testSaveThrowsExceptionWhenApiKeyWithNameExists(): void
    {
        $encodedApiKey = 'encodedApiKey123';
        $name = 'existingApiKey';

        $this->apiKeyRepository->save($encodedApiKey, $name);

        $this->expectException(ApiKeyException::class);
        $this->expectExceptionMessage("An API key with the name '$name' already exists.");

        $this->apiKeyRepository->save($encodedApiKey, $name);
    }

    /**
     * @throws ApiKeyException
     */
    public function testDeleteByIdRemovesApiKey(): void
    {
        $encodedApiKey = 'encodedApiKey123';
        $name = 'apiKeyToDelete';

        $apiKey = $this->apiKeyRepository->save($encodedApiKey, $name);

        $this->assertNotNull($apiKey);

        $result = $this->apiKeyRepository->deleteById($apiKey->getId());

        $this->assertTrue($result);
    }

    public function testDeleteByIdThrowsExceptionWhenApiKeyNotFound(): void
    {
        $this->expectException(ApiKeyException::class);
        $this->expectExceptionMessage("An API key doesn't exists.");

        $this->apiKeyRepository->deleteById(999);
    }

    public function testCheckApiKeyReturnsTrueWhenApiKeyExists(): void
    {
        $encodedApiKey = 'encodedApiKey123';
        $name = 'checkApiKey';

        $this->apiKeyRepository->save($encodedApiKey, $name);

        $result = $this->apiKeyRepository->checkApiKey($encodedApiKey);

        $this->assertTrue($result);
    }

    public function testCheckApiKeyReturnsFalseWhenApiKeyDoesNotExist(): void
    {
        $result = $this->apiKeyRepository->checkApiKey('nonExistentApiKey');
        $this->assertFalse($result);
    }

    protected function tearDown(): void
    {
        // Clear the database after tests
        $this->entityManager->clear();
    }
}
