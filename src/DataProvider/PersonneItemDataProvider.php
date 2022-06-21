<?php

namespace App\DataProvider;

use App\Entity\Personne;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;

final class PersonneItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Personne::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Personne
    {
        $personne = $this->em->getRepository(Personne::class)->find($id);
        if ($personne) {
            $personne->setNom(strtoupper($personne->getNom()));
        }
        return $personne;
    }
}
