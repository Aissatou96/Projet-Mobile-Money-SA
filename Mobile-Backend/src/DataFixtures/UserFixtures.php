<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\DataFixtures\ProfilFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const USER = 'User';
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $profils  = ['AdminSystem', 'AdminAgence', 'Caissier', 'UserAgence'];
        $faker = Factory::create('fr_FR');
         for ($i=0; $i < count($profils) ; $i++) { 
             for ($j=0; $j < 3 ; $j++) { 
               if($profils[$i] == 'AdminSystem'){
                   $user = new User();
                   $user->setProfil($this->getReference(ProfilFixtures::ADMINSYS_REF));
                   $manager->persist($user);
               }elseif ($profils[$i] == 'AdminAgence') {
                    $user = new User();
                    $user->setProfil($this->getReference(ProfilFixtures::ADMINAGENCE_REF));
                    $manager->persist($user);
               }elseif ($profils[$i] == 'Caissier') {
                    $user = new User();
                    $user->setProfil($this->getReference(ProfilFixtures::CAISSIER_REF));
                    $manager->persist($user);
               }elseif ($profils[$i] == 'UserAgence') {
                    $user = new User();
                    $user->setProfil($this->getReference(ProfilFixtures::USER_REF));
                    $manager->persist($user);
               }

               $user->setFirstname($faker->firstName())
                    ->setLastname($faker->lastName())
                    ->setEmail($faker->email())
                    ->setTelephone($faker->phoneNumber())
                    ->setPassword($this->encoder->encodePassword($user, "passer123"))
                    ->setAvatar($faker->imageUrl($width = 640, $height = 480))
                    ;
                    
                    $manager->persist($user);

                    $this->setReference(self::USER.$j,$user);    
             }
         }
                    $manager->flush();
        }

        public function getDependencies()
    {
        return [
            ProfilFixtures::class
        ];
    }
    }

   

