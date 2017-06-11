<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\JokePost;
use AppBundle\Form\JokePostType;

class JokePostController extends Controller
{
    public function newAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $jokepost = new JokePost();
        $form = $this->createForm(JokePostType::class, $jokepost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jokepost = $form->getData();
            $jokepost->setDate(new \DateTime('NOW'));
            $jokepost->setVote(0);

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

    public function oneAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokepost = $repository->findOneById($id);

        return $this->render('default/showPost.html.twig', array(
            'joke' => $jokepost,
        ));
    }

    public function likeAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokepost = $repository->findOneById($id);
        $jokepost->setVote($jokepost->getVote() + 1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($jokepost);
        $em->flush();

        $this->addFlash('like', 'Congratulations, your liked this post!');

        return $this->redirectToRoute('jokepost-one', array('id' => $id));
        /*$response = new JsonResponse(
            array('post_id' => $id, 'like' => $jokepost->getVote())
        );

        return $response;*/
    }

    public function unlikeAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokepost = $repository->findOneById($id);
        $jokepost->setVote($jokepost->getVote() - 1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($jokepost);
        $em->flush();

        $this->addFlash('unlike', 'Congratulations, you unliked this post!');

        return $this->redirectToRoute('jokepost-one', array('id' => $id));
        /*$response = new JsonResponse(
            array('post_id' => $id, 'like' => $jokepost->getVote())
        );

        return $response;*/
    }
}
