<?php

namespace App\Controller;

use App\Entity\UsersInfos;

use App\Form\UsersInfosType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UsersInfosController extends AbstractController
{
    /**
     * @Route("/livraison", name="livraison")
     */
    public function index(Request $request, SessionInterface $session)
    {
        if( !$this->getUser())
        {
            $this->addFlash('error','Vous devez etre connecté pour acceder a cette page');

            return $this->redirectToRoute('app_login');
        } 
        if( !empty($session))
        {
            $this->addFlash('error','Votre panier est vide');

            return $this->redirectToRoute('panier');
        } 
        $user = $this->getUser();
        $usersinfos = new UsersInfos();
        $usersinfos->setUser($this->getUser());
        $form = $this->createForm(UsersInfosType::class, $usersinfos);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usersinfos);
            $entityManager->flush();
            $this->addFlash('message','adresse ajoutée avec success');
            return $this->redirectToRoute('livraison');
        }
        
        return $this->render('users_infos/index.html.twig', [
            'user'=> $user,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/livraison/adresse/suppression/{id}", name="suppressionadresse")
     */
    public function adresseSuppression($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $usersInfos = $entityManager->getRepository(UsersInfos::class)->find($id);
        
        
        if( $this->getUser()!= $usersInfos->getUser() || !$usersInfos)

        return $this->redirectToRoute('livraison');
        
        $entityManager->remove($usersInfos);
        $entityManager->flush();
        
        return $this->redirect ($this->generateUrl('livraison'));

    }

   
}
