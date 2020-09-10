<?php

namespace App\Controller;

use App\Form\EditProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index()
    {
        return $this->render('users/users.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    /**
     * @Route("/user/modifier", name="user_modifier")
     */

    public function editProfile(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);

            $entityManager->flush();

            $this->addFlash('message', 'Profil mis a jour');
            return $this->redirectToRoute('users');
        }

        return $this->render('users/editeProfile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/pass/modifier", name="user_pass_modifier")
     */

    public function editPass(Request $request, UserPasswordEncoderInterface $PasswordEncoder)
    {
        if($request->isMethod('post'))
        {
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            // On verifie si les mots de passe sont identiques
            if($request->request->get('password') == $request->request->get('password2'))
            {
                $user->setPassword($PasswordEncoder->encodePassword($user,$request->request->get('password')));

                $em->flush();
                $this->addFlash('message','Mot de passe a été modifié avec succès');

                return $this->redirectToRoute('users');
            }
            else
            {
                $this->addFlash('error', 'Les deux mot de passe ne sont pas identiques');
            }
        }

        return $this->render('users/editePass.html.twig');
    }
}
