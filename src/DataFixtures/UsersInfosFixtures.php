<?php

namespace App\DataFixtures;

use App\Entity\UsersInfos;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UsersInfosFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $userInfos = new UsersInfos();
        $userInfos->setTelephone('0778254498');
        $userInfos->setAdresse('85 Rue de la liberté');
        $userInfos->setVille('Paris');
        $userInfos->setPays('France');
        $userInfos->setCodePostal('75001');
        $userInfos->setUser($this->getReference('user'));

        $manager->persist($userInfos);

        $manager->flush();
        

    }
}
