<?php

namespace App\DataFixtures;

use App\Entity\Compte;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompteFixtures extends Fixture implements DependentFixtureInterface
{
    public const COMPTE = 'Compte';
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i < 5 ; $i++) { 
            $cpte = new Compte();
            $cpte->setNumCpte($faker->ean8)
                 ->setSolde($faker->numberBetween(100000,1000000))
                 ->setCreatedAt(new \DateTime('now'))
                 ;
            $manager->persist($cpte);
            $this->addReference(self::COMPTE.$i,$cpte);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return[
            UserFixtures::class
        ];
    }
}
