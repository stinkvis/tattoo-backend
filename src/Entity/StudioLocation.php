<?php

namespace App\Entity;

use App\Repository\StudioLocationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Ulid;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;

#[ORM\Entity(repositoryClass: StudioLocationRepository::class)]
#[ORM\Table(name: 'studio_locations')]
#[ORM\Index(columns: ['city', 'country_code'])]
#[ORM\Index(columns: ['lat', 'lng'])]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['studio_location:read']]),
        new Get(normalizationContext: ['groups' => ['studio_location:read']]),
        new Post(denormalizationContext: ['groups' => ['studio_location:write']], security: "is_granted('ROLE_ADMIN')"),
        new Put(denormalizationContext: ['groups' => ['studio_location:write']], security: "is_granted('ROLE_ADMIN')"),
        new Patch(denormalizationContext: ['groups' => ['studio_location:write']], security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['studio_location:read']],
    denormalizationContext: ['groups' => ['studio_location:write']]
)]
class StudioLocation
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    #[Groups(['studio_location:read', 'studio:read'])]
    private ?Ulid $id = null;

    #[ORM\ManyToOne(targetEntity: Studio::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    #[Groups(['studio_location:read', 'studio_location:write'])]
    private ?Studio $studio = null;

    #[ORM\Column(name: 'address_line1', type: 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['studio_location:read', 'studio_location:write'])]
    private ?string $addressLine1 = null;

    #[ORM\Column(name: 'address_line2', type: 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['studio_location:read', 'studio_location:write'])]
    private ?string $addressLine2 = null;

    #[ORM\Column(type: 'string', length: 120)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 120)]
    #[Groups(['studio_location:read', 'studio_location:write'])]
    private ?string $city = null;

    #[ORM\Column(name: 'postal_code', type: 'string', length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    #[Groups(['studio_location:read', 'studio_location:write'])]
    private ?string $postalCode = null;

    #[ORM\Column(name: 'country_code', type: 'string', length: 2)]
    #[Assert\NotBlank]
    #[Assert\Length(exactly: 2)]
    #[Groups(['studio_location:read', 'studio_location:write'])]
    private ?string $countryCode = null;

    #[ORM\Column(type: 'decimal', precision: 9, scale: 6, nullable: true)]
    #[Groups(['studio_location:read', 'studio_location:write'])]
    private ?string $lat = null;

    #[ORM\Column(type: 'decimal', precision: 9, scale: 6, nullable: true)]
    #[Groups(['studio_location:read', 'studio_location:write'])]
    private ?string $lng = null;

    #[ORM\Column(name: 'is_primary', type: 'boolean', options: ['default' => true])]
    #[Groups(['studio_location:read', 'studio_location:write'])]
    private bool $isPrimary = true;

    #[ORM\Column(name: 'is_active', type: 'boolean', options: ['default' => true])]
    #[Groups(['studio_location:read', 'studio_location:write'])]
    private bool $isActive = true;

    #[Groups(['studio_location:read'])]
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[Groups(['studio_location:read'])]
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getStudio(): ?Studio
    {
        return $this->studio;
    }

    public function setStudio(Studio $studio): self
    {
        $this->studio = $studio;

        return $this;
    }

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function setAddressLine1(?string $addressLine1): self
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function setAddressLine2(?string $addressLine2): self
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = strtoupper($countryCode);

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(?string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    public function setIsPrimary(bool $isPrimary): self
    {
        $this->isPrimary = $isPrimary;

        return $this;
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
