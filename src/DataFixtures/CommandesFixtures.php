<?php

namespace App\DataFixtures;

use App\Entity\Commandes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class CommandesFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $commande1 = new Commandes();
        $commande1->setDateCommande(new \DateTime());
        $commande1->setReference('78921');
        $commande1->setProduit(["0" =>["1" => "2"],
                                "1" =>["2" => "1"],
                                "2" =>["4" => "5"]   
                               ]);
    
        $manager->persist($commande1);

        $manager->flush();

        $this->addReference('commande1', $commande1);


    }
}
