<?php

namespace App\Entity;

use App\Repository\StudioRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Ulid;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

#[ORM\Entity(repositoryClass: StudioRepository::class)]
#[ORM\Table(name: 'studios')]
#[ORM\UniqueConstraint(columns: ['slug'])]
#[ORM\Index(columns: ['name'])]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('slug')]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['studio:read']]),
        new Get(normalizationContext: ['groups' => ['studio:read']]),
        new Post(
            denormalizationContext: ['groups' => ['studio:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Put(
            denormalizationContext: ['groups' => ['studio:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
    ],
    normalizationContext: ['groups' => ['studio:read']],
    denormalizationContext: ['groups' => ['studio:write']]
)]
class Studio implements SlugAwareInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    #[Groups(['studio:read', 'studio_location:read', 'studio_artist:read'])]
    private ?Ulid $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Groups(['studio:read', 'studio:write', 'studio_location:read', 'studio_artist:read'])]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups(['studio:read'])]
    private ?string $slug = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['studio:read', 'studio:write'])]
    private ?string $bio = null;

    #[ORM\Column(name: 'logo_url', type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    #[Groups(['studio:read', 'studio:write'])]
    private ?string $logoUrl = null;

    #[ORM\Column(name: 'cover_image_url', type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    #[Groups(['studio:read', 'studio:write'])]
    private ?string $coverImageUrl = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Groups(['studio:read', 'studio:write'])]
    private ?string $phone = null;

    #[ORM\Column(type: 'string', length: 180, nullable: true)]
    #[Assert\Email]
    #[Groups(['studio:read', 'studio:write'])]
    private ?string $email = null;

    #[ORM\Column(name: 'website_url', type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    #[Groups(['studio:read', 'studio:write'])]
    private ?string $websiteUrl = null;

    #[ORM\Column(name: 'instagram_handle', type: 'string', length: 255, nullable: true)]
    #[Groups(['studio:read', 'studio:write'])]
    private ?string $instagramHandle = null;

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

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getLogoUrl(): ?string
    {
        return $this->logoUrl;
    }

    public function setLogoUrl(?string $logoUrl): self
    {
        $this->logoUrl = $logoUrl;

        return $this;
    }

    public function getCoverImageUrl(): ?string
    {
        return $this->coverImageUrl;
    }

    public function setCoverImageUrl(?string $coverImageUrl): self
    {
        $this->coverImageUrl = $coverImageUrl;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(?string $websiteUrl): self
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    public function getInstagramHandle(): ?string
    {
        return $this->instagramHandle;
    }

    public function setInstagramHandle(?string $instagramHandle): self
    {
        $this->instagramHandle = $instagramHandle;

        return $this;
    }

    #[Groups(['studio:read'])]
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[Groups(['studio:read'])]
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
