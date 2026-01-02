<?php

namespace App\Entity;

use App\Repository\StudioArtistRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Ulid;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;

#[ORM\Entity(repositoryClass: StudioArtistRepository::class)]
#[ORM\Table(name: 'studio_artists')]
#[ORM\UniqueConstraint(columns: ['studio_id', 'artist_profile_id'])]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['studio_artist:read']],
            uriTemplate: '/studio_artists'
        ),
        new Post(
            denormalizationContext: ['groups' => ['studio_artist:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Patch(
            denormalizationContext: ['groups' => ['studio_artist:write']],
            security: "is_granted('ROLE_ADMIN')"
        ),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['studio_artist:read']],
    denormalizationContext: ['groups' => ['studio_artist:write']]
)]
class StudioArtist
{
    use TimestampableTrait;

    public const ROLE_RESIDENT = 'resident';
    public const ROLE_GUEST = 'guest';
    public const ROLE_OWNER = 'owner';

    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    #[Groups(['studio_artist:read'])]
    private ?Ulid $id = null;

    #[ORM\ManyToOne(targetEntity: Studio::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    #[Groups(['studio_artist:read', 'studio_artist:write'])]
    private ?Studio $studio = null;

    #[ORM\ManyToOne(targetEntity: ArtistProfile::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    #[Groups(['studio_artist:read', 'studio_artist:write'])]
    private ?ArtistProfile $artistProfile = null;

    #[ORM\Column(type: 'string', length: 20, options: ['default' => self::ROLE_RESIDENT])]
    #[Assert\Choice(choices: [self::ROLE_RESIDENT, self::ROLE_GUEST, self::ROLE_OWNER])]
    #[Groups(['studio_artist:read', 'studio_artist:write'])]
    private string $role = self::ROLE_RESIDENT;

    #[ORM\Column(name: 'is_primary', type: 'boolean', options: ['default' => false])]
    #[Groups(['studio_artist:read', 'studio_artist:write'])]
    private bool $isPrimary = false;

    #[ORM\Column(name: 'start_date', type: 'date', nullable: true)]
    #[Groups(['studio_artist:read', 'studio_artist:write'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(name: 'end_date', type: 'date', nullable: true)]
    #[Groups(['studio_artist:read', 'studio_artist:write'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(name: 'is_active', type: 'boolean', options: ['default' => true])]
    #[Groups(['studio_artist:read', 'studio_artist:write'])]
    private bool $isActive = true;

    #[Groups(['studio_artist:read'])]
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[Groups(['studio_artist:read'])]
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

    public function getArtistProfile(): ?ArtistProfile
    {
        return $this->artistProfile;
    }

    public function setArtistProfile(ArtistProfile $artistProfile): self
    {
        $this->artistProfile = $artistProfile;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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
