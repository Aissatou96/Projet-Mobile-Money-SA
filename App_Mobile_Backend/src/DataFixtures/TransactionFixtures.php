<?php

namespace App\DataFixtures;

use App\Entity\Transaction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TransactionFixtures extends Fixture implements DependentFixtureInterface
{
    public const TRANSACTION = 'Transaction';
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i < 5; $i++) { 
          $transac = new Transaction();
          $transac->setMontant($faker->numberBetween(100000,1000000))
                  ->setCode($faker->ean8)
                  ->setDateDepot(new \DateTime('now'))
                  ->setDateRetrait(new \DateTime('now'))
                  ->setFrais($faker->numberBetween(1000,10000))
          ;
          $manager->persist($transac);
          $this->addReference(self::TRANSACTION.$i,$transac);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return[
            UserFixtures::class,
            ClientFixtures::class,
            CompteFixtures::class
        ];
    }
}
