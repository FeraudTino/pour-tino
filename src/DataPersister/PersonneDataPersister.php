<?php

namespace App\DataPersister;

use App\Entity\Personne;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class PersonneDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Personne;
    }

    public function persist($data, array $context = [])
    {
        if (($context['collection_operation_name'] ?? null) == "post") {
            $dateTimeZone = new \DateTimeZone('Europe/Paris');
            $data->setDateEnregistrement(new \DateTime('now', $dateTimeZone));
        }

        $this->em->persist($data);
        $this->em->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}
