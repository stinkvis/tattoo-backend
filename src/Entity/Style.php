<?php

namespace App\Entity;

use App\Repository\StyleRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Ulid;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

#[ORM\Entity(repositoryClass: StyleRepository::class)]
#[ORM\Table(name: 'styles')]
#[ORM\UniqueConstraint(columns: ['name'])]
#[ORM\UniqueConstraint(columns: ['slug'])]
#[ORM\Index(columns: ['name'])]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('name')]
#[UniqueEntity('slug')]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['style:read']]),
        new Get(normalizationContext: ['groups' => ['style:read']]),
        new Post(denormalizationContext: ['groups' => ['style:write']], security: "is_granted('ROLE_ADMIN')"),
        new Put(denormalizationContext: ['groups' => ['style:write']], security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['style:read']],
    denormalizationContext: ['groups' => ['style:write']]
)]
class Style implements SlugAwareInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    #[Groups(['style:read', 'artist_style:read'])]
    private ?Ulid $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Groups(['style:read', 'style:write', 'artist_style:read'])]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups(['style:read'])]
    private ?string $slug = null;

    #[ORM\Column(name: 'is_active', type: 'boolean', options: ['default' => true])]
    #[Groups(['style:read', 'style:write'])]
    private bool $isActive = true;

    #[Groups(['style:read'])]
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[Groups(['style:read'])]
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlugSource(): ?string
    {
        return $this->name;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
