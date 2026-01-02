<?php

namespace App\Entity;

interface SlugAwareInterface
{
    public function getSlug(): ?string;

    public function setSlug(string $slug): void;

    public function getSlugSource(): ?string;
}
