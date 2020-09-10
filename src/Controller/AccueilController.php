<?php

namespace App\Controller;

use App\Entity\Produits;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $produits = $entityManager->getRepository(Produits::class)->getLastInserted('App:Produits', 3);

        return $this->render('accueil/index.html.twig', [
            'produits' => $produits,
        ]);
        // return $this->render('accueil/index.html.twig', []);
    }
}
