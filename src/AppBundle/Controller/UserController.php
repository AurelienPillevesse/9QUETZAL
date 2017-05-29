<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
}
