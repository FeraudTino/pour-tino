<?php

namespace App\DataProvider;

use App\Entity\Personne;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;


final class PersonneCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Personne::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $personnes = $this->em->getRepository(Personne::class)->findAll();
        return array_map(fn ($elt) => $elt->setNom(strtoupper($elt->getNom())), $personnes);
    }
}
