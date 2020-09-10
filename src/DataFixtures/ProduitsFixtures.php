<?php

namespace App\DataFixtures;

use App\Entity\Produits;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class ProduitsFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $produit1 = new Produits();
        $produit1->setNom('gel hydroalcoolique');
        $produit1->setReference('25B878A');
        $produit1->setPrix(20);
        $produit1->setStock(158);
        $produit1->setCaracteristiques("Lorem est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq");
        $produit1->setRisques('On sait depuis longtemps que travailler il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles');
        $produit1->setDescription('Contrairement à une opinion répandue, le Lorem Ipsum n\'est pas simplement du texte aléatoire.');
        $produit1->setImage('image.jpg');
        $produit1->setCategorie($this->getReference('categorie2'));
        $produit1->setPromotion(1);
        $produit1->setTva(20);
    
        $manager->persist($produit1);

        $this->addReference('produit1', $produit1);

        $produit2 = new Produits();
        $produit2->setNom('Pack d\'essuyage bobines et distributeur TORK');
        $produit2->setReference('25B8963');
        $produit2->setPrix(159);
        $produit2->setStock(15);
        $produit2->setCaracteristiques("Lorem est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq");
        $produit2->setRisques('On sait depuis longtemps que travailler il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles');
        $produit2->setDescription('Contrairement à une opinion répandue, le Lorem Ipsum n\'est pas simplement du texte aléatoire.');
        $produit2->setImage('image3.jpg');
        $produit2->setCategorie($this->getReference('categorie4'));
        $produit2->setPromotion(1);
        $produit2->setTva(5);
    
        $manager->persist($produit2);


        $produit3 = new Produits();
        $produit3->setNom('Détergent multi-surfaces désinfectant ANIOS');
        $produit3->setReference('2PIO963');
        $produit3->setPrix(18);
        $produit3->setStock(159);
        $produit3->setCaracteristiques("Lorem est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq");
        $produit3->setRisques('On sait depuis longtemps que travailler il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles');
        $produit3->setDescription('Contrairement à une opinion répandue, le Lorem Ipsum n\'est pas simplement du texte aléatoire.');
        $produit3->setImage('image4.jpg');
        $produit3->setCategorie($this->getReference('categorie4'));
        $produit3->setPromotion(0);
        $produit3->setTva(2.2);
    
        $manager->persist($produit3);


        $manager->flush();
        
        
    }
}