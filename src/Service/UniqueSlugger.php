<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UniqueSlugger
{
    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function generate(string $source, string $entityClass, string $field = 'slug'): string
    {
        $baseSlug = strtolower($this->slugger->slug($source)->toString());
        $slug = $baseSlug;
        $i = 1;

        while ($this->slugExists($entityClass, $field, $slug)) {
            $slug = sprintf('%s-%d', $baseSlug, ++$i);
        }

        return $slug;
    }

    private function slugExists(string $entityClass, string $field, string $slug): bool
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('1')
            ->from($entityClass, 'e')
            ->where($qb->expr()->eq(sprintf('e.%s', $field), ':slug'))
            ->setParameter('slug', $slug)
            ->setMaxResults(1);

        return (bool) $qb->getQuery()->getOneOrNullResult();
    }
}
