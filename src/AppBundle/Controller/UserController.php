<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;

class UserController extends Controller
{
    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $users = $repository->findAll();

        return $this->render('default/listUser.html.twig', array(
            'users' => $users,
            ));
    }

    /*public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());

            $repository = $this->getDoctrine()->getRepository('AppBundle:User');
            $userVerification = $repository->findOneByUsername($user->getUsername());

            if($userVerification != null && $userVerification) {

            }

            return $this->redirectToRoute('user-list');
        }

        return $this->render(
            'signin/sign.html.twig',
            array('form' => $form->createView())
        );
    }*/
}
