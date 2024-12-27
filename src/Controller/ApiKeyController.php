<?php
declare(strict_types=1);

namespace App\Controller;

use App\Exception\ApiKeyException;
use App\Service\ApiKeyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ApiKeyController extends AbstractController
{
    private ApiKeyService $apiKeyService;

    public function __construct(ApiKeyService $apiKeyService)
    {
        $this->apiKeyService = $apiKeyService;
    }
    #[Route('/api/keys', name: 'generate_api_key', methods: ['POST'])]
    public function createApiKey(Request $request): Response
    {
        try {
            $name = $request->request->get('name');
            $apiKey = $this->apiKeyService->generateAndSaveApiKey($name);
            return $this->json($apiKey, Response::HTTP_CREATED);
        } catch (ApiKeyException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    #[Route('/api/keys/{id}', name: 'delete_api_key', methods: ['DELETE'])]
    public function deleteApiKey(int $id): JsonResponse
    {
            try {
                $this->apiKeyService->deleteApiKey($id);
                return new JsonResponse(['message' => 'API key deleted successfully'], Response::HTTP_OK);
            } catch (ApiKeyException) {
                return new JsonResponse(['error' => 'API key not found'], Response::HTTP_NOT_FOUND);
            }
             catch (\Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
    }
    #[Route('/api/keys', name: 'get_all_api_keys', methods: ['GET'])]
    public function getAllApiKeys(SerializerInterface $serializer): JsonResponse
    {
        try {
            $apiKeys = $this->apiKeyService->getAllApiKeys();
            return new JsonResponse(
                $serializer->normalize($apiKeys, null, ['groups' => 'api_key_list']),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
