<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Comment;
use AppBundle\Form\CommentType;

class CommentController extends Controller
{
    public function newAction($idJokePost, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();

            $jokePostRepository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
            $jokepost = $jokePostRepository->findOneById($idJokePost);

            $comment->setJokepost($jokepost);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('jokepost-list');
        }

        return $this->render('default/createComment.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function oneAction($idJokePost, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Comment');
        $comments = $jokePostRepository->findByJokePost($idJokePost);

        var_dump($comments);
        die;
    }
}
