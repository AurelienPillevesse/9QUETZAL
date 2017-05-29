<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use AppBundle\Entity\JokePost;

class JokePostController extends Controller
{
    public function newAction(Request $request)
    {
        $jokepost = new JokePost();

        $form = $this->createFormBuilder($jokepost)
        ->add('title', TextType::class)
        ->add('img', FileType::class, array('label' => 'Image du post'))
        ->add('save', SubmitType::class, array('label' => 'Create JokePost'))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jokepost = $form->getData();
            $jokepost->setDate(new \DateTime('NOW'));

            $imgFile = $jokepost->getImg();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$imgFile->guessExtension();

            // Move the file to the directory where brochures are stored
            $imgFile->move(
                $this->getParameter('jokepost_directory'),
                $fileName
                );

            $jokepost->setImg($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($jokepost);
            $em->flush();

            return $this->redirectToRoute('jokepost-list');
        }

        return $this->render('default/createPost.html.twig', array(
            'form' => $form->createView(),
            ));
    }

    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokeposts = $repository->findAll();

        return $this->render('default/listPost.html.twig', array(
            'jokes' => $jokeposts,
            ));
    }

    public function oneAction($id){
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokepost = $repository->findOneById($id);

        return $this->render('default/showPost.html.twig', array(
            'joke' => $jokepost,
            ));
    }
}
