<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientFixtures extends Fixture
{
    public const CLIENT = 'client';
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i < 5 ; $i++) { 
            $client = new Client();
            $client->setFirstname($faker->firstName())
                   ->setLastname($faker->lastName())
                   ->setPhone($faker->phoneNumber)
                   ->setCni($faker->ean13);
            $manager->persist($client);
            $this->setReference(self::CLIENT.$i,$client);
        }
        $manager->flush();
    }
}
