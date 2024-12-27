<?php
declare(strict_types=1);
namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ApiKeyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApiKeyRepository::class)]
class ApiKey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("api_key_list")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("api_key_list")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups("api_key_list")]
    private ?string $apiKeyValue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getApiKeyValue(): ?string
    {
        return $this->apiKeyValue;
    }

    public function setApiKeyValue(string $apiKeyValue): static
    {
        $this->apiKeyValue = $apiKeyValue;

        return $this;
    }
}
