<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\JokePostType;
use AppBundle\Form\CommentType;
use AppBundle\Entity\JokePost;
use AppBundle\Entity\Comment;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

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

    public function listApiAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokeposts = $repository->findAll();

        $encoders = new JsonEncoder();
        $normalizers = new ObjectNormalizer();

        $normalizers->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer(array($normalizers), array($encoders));
        $jsonContent = $serializer->serialize($jokeposts, 'json');

        return new JsonResponse($jsonContent);
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
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokepost = $repository->findOneById($id);
        $jokepost->setVote($jokepost->getVote() + 1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($jokepost);
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

        $encoders = new JsonEncoder();
        $normalizers = new ObjectNormalizer();

        $normalizers->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer(array($normalizers), array($encoders));
        $jsonContent = $serializer->serialize($jokepost, 'json');

        return new JsonResponse($jsonContent);
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

        $encoders = new JsonEncoder();
        $normalizers = new ObjectNormalizer();

        $normalizers->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer(array($normalizers), array($encoders));
        $jsonContent = $serializer->serialize($jokepost, 'json');

        return new JsonResponse($jsonContent);
    }
}
