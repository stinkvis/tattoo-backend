<?php

namespace App\Entity;

use App\Repository\ArtistProfileRepository;
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

#[ORM\Entity(repositoryClass: ArtistProfileRepository::class)]
#[ORM\Table(name: 'artist_profiles')]
#[ORM\UniqueConstraint(columns: ['slug'])]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('slug')]
#[UniqueEntity('user')]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['artist_profile:read']]),
        new Get(normalizationContext: ['groups' => ['artist_profile:read']]),
        new Post(
            denormalizationContext: ['groups' => ['artist_profile:write']],
            securityPostDenormalize: "is_granted('ROLE_USER') and object.getUser() == user"
        ),
        new Put(
            denormalizationContext: ['groups' => ['artist_profile:write']],
            security: "is_granted('ROLE_USER') and object.getUser() == user"
        ),
    ],
    normalizationContext: ['groups' => ['artist_profile:read']],
    denormalizationContext: ['groups' => ['artist_profile:write']]
)]
class ArtistProfile implements SlugAwareInterface
{
    use TimestampableTrait;

    public const CONTACT_INSTAGRAM = 'instagram';
    public const CONTACT_WEBSITE = 'website';
    public const CONTACT_EMAIL = 'email';
    public const CONTACT_PHONE = 'phone';

    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    #[Groups(['artist_profile:read'])]
    private ?Ulid $id = null;

    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'artistProfile')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['artist_profile:read', 'artist_profile:write'])]
    #[Assert\NotNull]
    private ?User $user = null;

    #[ORM\Column(name: 'display_name', type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Groups(['artist_profile:read', 'artist_profile:write'])]
    private ?string $displayName = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups(['artist_profile:read'])]
    private ?string $slug = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['artist_profile:read', 'artist_profile:write'])]
    private ?string $bio = null;

    #[ORM\Column(name: 'profile_image_url', type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    #[Groups(['artist_profile:read', 'artist_profile:write'])]
    private ?string $profileImageUrl = null;

    #[ORM\Column(name: 'cover_image_url', type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    #[Groups(['artist_profile:read', 'artist_profile:write'])]
    private ?string $coverImageUrl = null;

    #[ORM\Column(name: 'website_url', type: 'string', length: 255, nullable: true)]
    #[Assert\Url]
    #[Groups(['artist_profile:read', 'artist_profile:write'])]
    private ?string $websiteUrl = null;

    #[ORM\Column(name: 'instagram_handle', type: 'string', length: 255, nullable: true)]
    #[Groups(['artist_profile:read', 'artist_profile:write'])]
    private ?string $instagramHandle = null;

    #[ORM\Column(name: 'contact_preference', type: 'string', length: 20, nullable: true)]
    #[Assert\Choice(choices: [self::CONTACT_INSTAGRAM, self::CONTACT_WEBSITE, self::CONTACT_EMAIL, self::CONTACT_PHONE])]
    #[Groups(['artist_profile:read', 'artist_profile:write'])]
    private ?string $contactPreference = null;

    #[ORM\Column(name: 'is_verified', type: 'boolean', options: ['default' => false])]
    #[Groups(['artist_profile:read'])]
    private bool $isVerified = false;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

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
        return $this->displayName;
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

    public function getProfileImageUrl(): ?string
    {
        return $this->profileImageUrl;
    }

    public function setProfileImageUrl(?string $profileImageUrl): self
    {
        $this->profileImageUrl = $profileImageUrl;

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

    public function getContactPreference(): ?string
    {
        return $this->contactPreference;
    }

    public function setContactPreference(?string $contactPreference): self
    {
        $this->contactPreference = $contactPreference;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    #[Groups(['artist_profile:read'])]
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[Groups(['artist_profile:read'])]
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
