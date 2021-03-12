<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixtures extends Fixture
{
        public const ADMINSYS_REF = "AdminSystem";
        public const ADMINAGENCE_REF = "AdminAgence";
        public const CAISSIER_REF = "Caissier";
        public const USER_REF = "UserAgence";

    public function load(ObjectManager $manager)
    { 
        $profil = new Profil();
        $profil->setLibelle(self::ADMINSYS_REF);
        $manager->persist($profil);
        $this->addReference(self::ADMINSYS_REF,$profil);

        $profil = new Profil();
        $profil->setLibelle(self::ADMINAGENCE_REF);
        $manager->persist($profil);
        $this->addReference(self::ADMINAGENCE_REF,$profil);

        $profil = new Profil();
        $profil->setLibelle(self::CAISSIER_REF);
        $manager->persist($profil);
        $this->addReference(self::CAISSIER_REF,$profil);

        $profil = new Profil();
        $profil->setLibelle(self::USER_REF);
        $manager->persist($profil);
        $this->addReference(self::USER_REF,$profil);

        
        $manager->flush();
    }
}
