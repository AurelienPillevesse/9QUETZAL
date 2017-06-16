<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

    public function registerApiAction(Request $request)
    {
        $formFactory = $this->get('fos_user.registration.form.factory');
        $userManager = $this->get('fos_user.user_manager');
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);
        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm([
            'csrf_protection' => false,
        ]);

        $form->setData($user);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);
            if (null !== $response = $event->getResponse()) {
                return $response;
            }

            return $form;
        }

        $event = new FormEvent($form, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            return new JsonResponse(
                [
                    'msg' => $this->get('translator')->trans('registration.flash.user_created', [], 'FOSUserBundle'),
                    'token' => $this->get('lexik_jwt_authentication.jwt_manager')->create($user), // creates JWT
                ],
                Response::HTTP_CREATED
            );
        }

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
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
