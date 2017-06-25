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
use Symfony\Component\DependencyInjection\ContainerInterface;

class JokePostController extends Controller
{
    private $serializer;

    /**
     * Method that permit to set the instance of the container.
     *
     * @param $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->serializer = $this->get('app.serializer.default');
    }

    /**
     * Create new JokePost.
     *
     * @param $request
     * @param $id
     *
     * @return JsonResponse
     */
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

    /**
     *  Get all the post.
     *
     * @param $request
     *
     * @return Twig render
     */
    public function allAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokeposts = $repository->findBy(array(), array('date' => 'DESC'));

        return $this->render('default/listPost.html.twig', array(
            'jokes' => $jokeposts,
        ));
    }

    /**
     *  Get all the post directly by the API.
     *
     * @param $request
     *
     * @return JsonResponse
     */
    public function allApiAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokeposts = $repository->findBy(array(), array('date' => 'DESC'));

        return new JsonResponse($this->serializer->serialize($jokeposts, 'json'), 200);
    }

    /**
     *  Get on JokePost by the id.
     *
     * @param $request
     * @param $id
     *
     * @return Twig render
     */
    public function oneAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokepost = $repository->findOneById($id);

        if (!$jokepost) {
            throw $this->createNotFoundException('This jokepost does not exist');
        }

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

    /**
     *  Get on JokePost by the id with the API.
     *
     * @param $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function oneApiAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokepost = $repository->findOneById($id);

        if (!$jokepost) {
            return new JsonResponse(['message' => 'This jokepost does not exist'], 404);
        }

        return new JsonResponse($this->serializer->serialize($jokepost, 'json'), 200);
    }
}
