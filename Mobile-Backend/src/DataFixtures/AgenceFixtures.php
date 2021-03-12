<?php

namespace App\DataFixtures;

use App\Entity\Agence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AgenceFixtures extends Fixture implements DependentFixtureInterface
{
    public const AGENCE = 'Agence';
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i < 5 ; $i++) { 
           $agence = new Agence();
           $agence->setName($faker->name())
                  ->setAdress($faker->address)
           ;
           $this->getReference(CompteFixtures::COMPTE.$i,$agence);
           $manager->persist($agence);
           $this->addReference(self::AGENCE.$i,$agence);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return[
            CompteFixtures::class
        ];
    }
}


