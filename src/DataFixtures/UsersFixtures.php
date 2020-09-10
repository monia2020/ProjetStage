<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new Users();
        $user->setNom('admin');
        $user->setPrenom('admin');
        $user->setEmail('admin1@test.fr');
        $user->setRoles(['ROLE_ADMIN']);
        $user->addCommande($this->getReference('commande1'));



        $password = $this->encoder->encodePassword($user, 'pass_1234');
        $user->setPassword($password);
        $manager->persist($user);

        $manager->flush();

        $this->addReference('user', $user);
    }
}
