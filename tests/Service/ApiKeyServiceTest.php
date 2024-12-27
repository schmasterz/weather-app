<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\ApiKey;
use App\Exception\ApiKeyException;
use App\Repository\ApiKeyRepository;
use App\Service\ApiKeyService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ApiKeyServiceTest extends TestCase
{
    /** @var ApiKeyService */
    private $apiKeyService;

    /** @var MockObject|EntityManagerInterface */
    private $entityManager;

    /** @var MockObject|ApiKeyRepository */
    private $apiKeyRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->apiKeyRepository = $this->createMock(ApiKeyRepository::class);

        $this->apiKeyService = new ApiKeyService(
            $this->entityManager,
            $this->apiKeyRepository
        );
    }

    /**
     * @throws ApiKeyException
     */
    public function testGenerateAndSaveApiKey()
    {
        $name = 'test-api-key';

        $this->apiKeyRepository
            ->expects($this->once())
            ->method('save')
            ->withAnyParameters()
            ->willReturn(new ApiKey());

        $this->apiKeyService->generateAndSaveApiKey($name);
    }

    public function testDeleteApiKey()
    {
        $id = 1;

        // Mocking the deleteById method
        $this->apiKeyRepository
            ->expects($this->once())
            ->method('deleteById')
            ->with($id)
            ->willReturn(true);

        // Calling the deleteApiKey method
        $result = $this->apiKeyService->deleteApiKey($id);

        // Asserting that the result is true
        $this->assertTrue($result);
    }

    public function testGetAllApiKeys()
    {
        // Mocking the result of findAll to return an array of ApiKey entities
        $mockApiKey = $this->createMock(ApiKey::class);

        // Mock the getRepository() to return a mock of ApiKeyRepository
        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(ApiKey::class)
            ->willReturn($this->apiKeyRepository);

        // Mocking the findAll method on the repository to return an array of ApiKey entities
        $this->apiKeyRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$mockApiKey]);

        // Calling the getAllApiKeys method
        $result = $this->apiKeyService->getAllApiKeys();

        // Asserting that the result is an array of ApiKey entities
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    public function testVerifyApiKey()
    {
        $apiKey = 'some-api-key';

        // Mocking the checkApiKey method
        $this->apiKeyRepository
            ->expects($this->once())
            ->method('checkApiKey')
            ->with($apiKey)
            ->willReturn(true);

        // Calling the verifyApiKey method
        $result = $this->apiKeyService->verifyApiKey($apiKey);

        // Asserting that the result is true
        $this->assertTrue($result);
    }

}
