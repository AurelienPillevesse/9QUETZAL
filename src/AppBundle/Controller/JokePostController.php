<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\JokePostType;
use AppBundle\Form\CommentType;
use AppBundle\Entity\JokePost;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Vote;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class JokePostController extends Controller
{
    private $serializer;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->serializer = $this->get('app.serializer.default');
    }

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
            $jokepost->setAuthor($this->getUser());

            $imgFile = $jokepost->getImg();
            $fileName = md5(uniqid()).'.'.$imgFile->guessExtension();
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

    public function allAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokeposts = $repository->findBy(array(), array('date' => 'DESC'));

        return $this->render('default/listPost.html.twig', array(
            'jokes' => $jokeposts,
        ));
    }

    public function listApiAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokeposts = $repository->findBy(array(), array('date' => 'DESC'));

        return new JsonResponse($this->serializer->serialize($jokeposts, 'json'), 200);
    }

    public function oneAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokepost = $repository->findOneById($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
                throw $this->createAccessDeniedException();
            }

            $comment = $form->getData();
            $comment->setJokepost($jokepost);
            $comment->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash('comment', 'Congratulations, your posted a comment!');

            return $this->redirectToRoute('jokepost-one', array('id' => $id));
        }

        return $this->render('default/showPost.html.twig', array(
            'joke' => $jokepost,
            'form' => $form->createView(),
        ));
    }

    public function likeAction(Request $request, $id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $jokepostRepo = $em->getRepository('AppBundle:JokePost');
        $voteRepo = $em->getRepository('AppBundle:Vote');

        $jokepost = $jokepostRepo->findOneById($id);
        $vote = $voteRepo->findOneBy(['jokepost' => $jokepost, 'user' => $this->getUser()]);

        if (!$vote) {
            $vote = new Vote();
            $vote->setJokepost($jokepost);
            $vote->setUser($this->getUser());
            $jokepost->voteUp();
        }

        if ($vote->getDown()) {
            $jokepost->voteDownToUp();
        }

        $vote->voteUp();

        $em->persist($jokepost);
        $em->persist($vote);
        $em->flush();
        $this->addFlash('like', 'Congratulations, your liked this post!');

        return $this->redirectToRoute('jokepost-one', array('id' => $id));
    }

    public function unlikeAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $repositoryVote = $this->getDoctrine()->getRepository('AppBundle:Vote');
        $jokepost = $repository->findOneById($id);
        $vote = $repositoryVote->findOneBy(['jokepost' => $jokepost, 'user' => $this->getUser()]);

        if (!$vote) {
            $vote = new Vote();
            $vote->setJokepost($jokepost);
            $vote->setUser($this->getUser());
            $jokepost->voteDown();
        }

        if ($vote->getUp()) {
            $jokepost->voteUpToDown();
        }

        $vote->voteDown();

        $em->persist($jokepost);
        $em->persist($vote);
        $em->flush();

        $this->addFlash('unlike', 'Ooooh, your unliked this post!');

        return $this->redirectToRoute('jokepost-one', array('id' => $id));
    }
}
