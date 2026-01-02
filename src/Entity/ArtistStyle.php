<?php

namespace App\Entity;

use App\Repository\ArtistStyleRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Ulid;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;

#[ORM\Entity(repositoryClass: ArtistStyleRepository::class)]
#[ORM\Table(name: 'artist_styles')]
#[ORM\UniqueConstraint(columns: ['artist_profile_id', 'style_id'])]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['artist_style:read']]),
        new Post(
            denormalizationContext: ['groups' => ['artist_style:write']],
            security: "is_granted('ROLE_USER')"
        ),
        new Delete(security: "is_granted('ROLE_USER')"),
    ],
    normalizationContext: ['groups' => ['artist_style:read']],
    denormalizationContext: ['groups' => ['artist_style:write']]
)]
class ArtistStyle
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    #[Groups(['artist_style:read'])]
    private ?Ulid $id = null;

    #[ORM\ManyToOne(targetEntity: ArtistProfile::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    #[Groups(['artist_style:read', 'artist_style:write'])]
    private ?ArtistProfile $artistProfile = null;

    #[ORM\ManyToOne(targetEntity: Style::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    #[Groups(['artist_style:read', 'artist_style:write'])]
    private ?Style $style = null;

    #[Groups(['artist_style:read'])]
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[Groups(['artist_style:read'])]
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getId(): ?Ulid
    {
        return $this->id;
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

    public function getStyle(): ?Style
    {
        return $this->style;
    }

    public function setStyle(Style $style): self
    {
        $this->style = $style;

        return $this;
    }
}
