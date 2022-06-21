<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Adresse;
use App\Entity\Personne;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($p = 0; $p < 10; $p++) {
            $personne = new Personne;
            $personne->setNom($faker->lastName);
            $personne->setPrenom($faker->firstName);
            $dateTimeZone = new \DateTimeZone('Europe/Paris');
            $personne->setDateEnregistrement(new \DateTime('now', $dateTimeZone));
            for ($c = 0; $c < mt_rand(1, 5); $c++) {
                $adresse = new Adresse;
                $adresse->setRue($faker->streetName);
                $adresse->setVille($faker->city);
                $adresse->setCodePostal($faker->postcode);
                $personne->addAdress($adresse);
            }
            $manager->persist($personne);
        }
        $manager->flush();
    }
}
