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
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
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
            $jokepost->setDate(new \DateTime('NOW'));
            $jokepost->setUpvotes(0);
            $jokepost->setDownvotes(0);
            $jokepost->setTotalvotes(0);

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

    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokeposts = $repository->findAll();

        return $this->render('default/listPost.html.twig', array(
            'jokes' => $jokeposts,
        ));
    }

    public function listApiAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokeposts = $repository->findAll();

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
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $repositoryVote = $this->getDoctrine()->getRepository('AppBundle:Vote');
        $jokepost = $repository->findOneById($id);
        $vote = $repositoryVote->findOneBy(['jokepost' => $jokepost, 'user' => $this->getUser()]);

        if (!$vote) {
            $vote = new Vote();
            $vote->setJokepost($jokepost);
            $vote->setUser($this->getUser());
            $jokepost->setUpvotes($jokepost->getUpvotes() + 1);
            $jokepost->setTotalvotes($jokepost->getTotalvotes() + 1);
        }

        if ($vote->getDown()) {
            $jokepost->setDownvotes($jokepost->getDownvotes() - 1);
            $jokepost->setUpvotes($jokepost->getUpvotes() + 1);
        }

        $vote->setUp(true);
        $vote->setDown(false);

        $em->persist($jokepost);
        $em->persist($vote);
        $em->flush();
        $this->addFlash('like', 'Congratulations, your liked this post!');

        return $this->redirectToRoute('jokepost-one', array('id' => $id));
    }

    public function likeApiAction(Request $request, $id)
    {
        $receivedData = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $APIKey = $em->getRepository('AppBundle:APIKey')->findOneByHash($receivedData['token']);

        if (!$APIKey) {
            throw new BadCredentialsException();
        }

        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokepost = $repository->findOneById($id);
        $jokepost->setVote($jokepost->getVote() + 1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($jokepost);
        $em->flush();

        return new JsonResponse($this->serializer->serialize($jokepost, 'json'), 200);
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
            $jokepost->setTotalvotes($jokepost->getTotalvotes() + 1);
            $jokepost->setDownvotes($jokepost->getDownvotes() + 1);
        }

        if ($vote->getUp()) {
            $jokepost->setUpvotes($jokepost->getUpvotes() - 1);
            $jokepost->setDownvotes($jokepost->getDownvotes() + 1);
        }

        $vote->setDown(true);
        $vote->setUp(false);

        $em->persist($jokepost);
        $em->persist($vote);
        $em->flush();
        $this->addFlash('unlike', 'Ooooh, your unliked this post!');

        return $this->redirectToRoute('jokepost-one', array('id' => $id));
    }

    public function unlikeApiAction(Request $request, $id)
    {
        $receivedData = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $APIKey = $em->getRepository('AppBundle:APIKey')->findOneByHash($receivedData['token']);

        if (!$APIKey) {
            throw new BadCredentialsException();
        }

        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokepost = $repository->findOneById($id);
        $jokepost->setVote($jokepost->getVote() - 1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($jokepost);
        $em->flush();

        return new JsonResponse($this->serializer->serialize($jokepost, 'json'), 200);
    }
}
