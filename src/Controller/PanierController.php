<?php

namespace App\Controller;

use App\Repository\ProduitsRepository;
use App\Entity\Produits;
use App\Entity\UsersInfos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(SessionInterface $session, ProduitsRepository $produitsRepository)
    {
             
        $panier = $session->get('panier', []);
        $panierWidthData = [];

        foreach($panier as $id => $quantite)
        {
            $panierWidthData[] = [
                'produit' => $produitsRepository->find($id),
                'quantite' => $quantite
            ];
        }
       
        $totalHT = 0 ;
        $totalTTC = 0 ;
        $totalTVA = 0 ;
        foreach($panierWidthData as $item)
        {
            $totalHTItem = $item['produit']->getPrix() * $item['quantite'];
            $totalTTCItem = $totalHTItem *(1 + (( $item['produit']->getTva())/100));
            $totalTVAItem = $totalHTItem * ($item['produit']->getTva()/100);

            $totalHT += $totalHTItem;
            $totalTTC += $totalTTCItem;
            $totalTVA += $totalTVAItem;
        }
        return $this->render('panier/index.html.twig', [
            'items' => $panierWidthData,
            'totalHT' => $totalHT,
            'totalTTC' => $totalTTC,
            'totalTVA' => $totalTVA,    
        ]);
    }

    /**
     * @Route("/panier/ajouter/{id}", name="panier_ajouter")
     */
    public function ajouter($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);

        if(!empty($panier[$id]))
        {
            $panier[$id]++;
        }
        else
        {
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute("panier");
    }

     /**
     * @Route("/panier/supprimer/{id}", name="panier_supprimer")
     */
    public function supprimer($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);

        if(!empty($panier[$id]))
        {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        $this->addFlash('message','Produit retiré avec succès de votre panier');

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/validation", name="validation", methods={"POST","GET"})
     */
    public function validationAction(Request $request, ProduitsRepository $produitsRepository)
    {
        // dd($request);
            
        if ($request->getMethod() == 'POST')
        { 
            $id_livraison = $request->request->get('livraison');    
        }

        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        // $produits = $em->getRepository(Produits::class)->findAll($session->get('panier'));
        $livraison = $em->getRepository(UsersInfos::class)->find($id_livraison);
        //  dd($session->get('panier'));
        $panier = $session->get('panier', []);
        $panierWidthData = [];

        foreach($panier as $id => $quantite)
        {
            $panierWidthData[] = [
                'produit' => $produitsRepository->find($id),
                'quantite' => $quantite
            ];
        }
       
        $totalHT = 0 ;
        $totalTTC = 0 ;
        $totalTVA = 0 ;
        foreach($panierWidthData as $item)
        {
            $totalHTItem = $item['produit']->getPrix() * $item['quantite'];
            $totalTTCItem = $totalHTItem *(1 + (( $item['produit']->getTva())/100));
            $totalTVAItem = $totalHTItem * ($item['produit']->getTva()/100);

            $totalHT += $totalHTItem;
            $totalTTC += $totalTTCItem;
            $totalTVA += $totalTVAItem;
        }
    
        return $this->render('panier/validation.html.twig',[
            // 'produits' => $produits,
            'panier' => $session->get('panier'),
            'livraison' => $livraison,
            'items' => $panierWidthData,
            'totalHT' => $totalHT,
            'totalTTC' => $totalTTC,
            'totalTVA' => $totalTVA,
        ]);
    }
        
}