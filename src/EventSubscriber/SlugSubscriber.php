<?php

namespace App\EventSubscriber;

use App\Entity\SlugAwareInterface;
use App\Service\UniqueSlugger;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]
class SlugSubscriber
{
    public function __construct(private readonly UniqueSlugger $uniqueSlugger)
    {
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->handleSlug($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->handleSlug($args);
    }

    private function handleSlug(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!$entity instanceof SlugAwareInterface) {
            return;
        }

        if ($entity->getSlug()) {
            return;
        }

        $source = $entity->getSlugSource();
        if (!$source) {
            return;
        }

        $slug = $this->uniqueSlugger->generate($source, $entity::class);
        $entity->setSlug($slug);
    }
}
