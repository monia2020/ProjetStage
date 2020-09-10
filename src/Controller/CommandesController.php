<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommandesController extends AbstractController
{
    /**
     * @Route("/commandes", name="commandes")
     */
    public function facture(Request $request, ProduitsRepository $produitsRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $generator = $this->container->get('security.secure_random');
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        $id_livraison = $request->request->get('livraison');    
        $livraison = $em->getRepository(UsersInfos::class)->find($id_livraison);
        $commande = [];
        $totalHT = 0;
        $totalTVA = 0;
        
        // foreach($produits as $produit)
        // {
        //     $prixHT = ($produit->getPrix() * $panier[$produit->getId()]);
        //     $prixTTC = ($produit->getPrix() * $panier[$produit->getId()] / $produit->getTva()->getMultiplicate());
        //     $totalHT += $prixHT;
            
        //     if (!isset($commande['tva']['%'.$produit->getTva()->getValeur()]))
        //         $commande['tva']['%'.$produit->getTva()->getValeur()] = round($prixTTC - $prixHT,2);
        //     else
        //         $commande['tva']['%'.$produit->getTva()->getValeur()] += round($prixTTC - $prixHT,2);
            
        //     $totalTVA += round($prixTTC - $prixHT,2);
            
        //     $commande['produit'][$produit->getId()] = array('reference' => $produit->getNom(),
        //                                                     'quantite' => $panier[$produit->getId()],
        //                                                     'prixHT' => round($produit->getPrix(),2),
        //                                                     'prixTTC' => round($produit->getPrix() / $produit->getTva()->getMultiplicate(),2));
        // } 

        foreach($panier as $id => $quantite)
        {
            $commande[] = [
                'produit' => $produitsRepository->find($id),
                'quantite' => $quantite
            ];
        }       
        $totalTTC = 0 ;
        foreach($commande as $item)
        {
            $totalHTItem = $item['produit']->getPrix() * $item['quantite'];
            $totalTTCItem = $totalHTItem *(1 + (( $item['produit']->getTva())/100));
            $totalTVAItem = $totalHTItem * ($item['produit']->getTva()/100);

            $totalHT += $totalHTItem;
            $totalTTC += $totalTTCItem;
            $totalTVA += $totalTVAItem;
        }
        // return $this->render('panier/index.html.twig', [
        //     'items' => $panierWidthData,
        //     'totalHT' => $totalHT,
        //     'totalTTC' => $totalTTC,
        //     'totalTVA' => $totalTVA,    
        // ]);

        
        $commande['livraison'] = array('prenom' => $livraison->getPrenom(),
                                    'nom' => $livraison->getNom(),
                                    'telephone' => $livraison->getTelephone(),
                                    'adresse' => $livraison->getAdresse(),
                                    'cp' => $livraison->getCp(),
                                    'ville' => $livraison->getVille(),
                                    'pays' => $livraison->getPays());

        $commande['prixHT'] = round($totalHT,2);
        $commande['prixTTC'] = round($totalHT + $totalTVA,2);
        $commande['token'] = bin2hex($generator->nextBytes(20));

        return $commande;
    }

    public function prepareCommande(Request $request)
    {
            $session = $request->getSession();
            $em = $this->getDoctrine()->getManager();
            
            if (!$session->has('commande'))
                $commande = new Commandes();
            else
                $commande = $em->getRepository(Commandes::class)->find($session->get('commande'));
            
            $commande->setDate(new \DateTime());
            $commande->setUtilisateur($this->container->get('security.context')->getToken()->getUser());
            $commande->setValider(0);
            $commande->setReference(0);
            $commande->setCommande($this->facture());
            
            if (!$session->has('commande')) {
                $em->persist($commande);
                $session->set('commande',$commande);
            }
            
            $em->flush();
            
            return new Response($commande->getId());
       
    }
}