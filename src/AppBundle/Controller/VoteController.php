<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\JokePost;
use AppBundle\Entity\Vote;
use AppBundle\Entity\APIKey;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class VoteController extends Controller
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
     *  Function that permit to like a JokePost.
     *
     * @param $id Id of a JokePost
     *
     * @return Twig render
     */
    public function likeAction(Request $request, $id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $jokepostRepo = $em->getRepository('AppBundle:JokePost');
        $voteRepo = $em->getRepository('AppBundle:Vote');

        $jokepost = $jokepostRepo->findOneById($id);

        if (!$jokepost) {
            throw $this->createNotFoundException('This jokepost does not exist');
        }

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

    /**
     *  Function that permit to unlike a JokePost.
     *
     * @param $id Id of a JokePost
     *
     * @return Twig render
     */
    public function unlikeAction($id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $repositoryVote = $this->getDoctrine()->getRepository('AppBundle:Vote');
        $jokepost = $repository->findOneById($id);

        if (!$jokepost) {
            throw $this->createNotFoundException('This jokepost does not exist');
        }

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

    /**
     * Method that like a Vote via the API.
     *
     * @param $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function likeApiAction(Request $request, $id)
    {
        $key = $this->serializer->deserialize($request->getContent(), APIKey::class, 'json');
        $em = $this->getDoctrine()->getManager();
        $APIKey = $em->getRepository('AppBundle:APIKey')->findOneByHash($key->getHash());

        if (!$APIKey) {
            throw new BadCredentialsException('Need a valid APIKey');
        }

        if (!$APIKey->isValid()) {
            throw new BadCredentialsException('Token expired');
        }

        $user = $APIKey->getUser();

        $jokepostRepo = $em->getRepository('AppBundle:JokePost');
        $voteRepo = $em->getRepository('AppBundle:Vote');

        $jokepost = $jokepostRepo->findOneById($id);

        if (!$jokepost) {
            return new JsonResponse(['message' => 'This jokepost does not exist'], 404);
        }

        $vote = $voteRepo->findOneBy(['jokepost' => $jokepost, 'user' => $user]);

        if (!$vote) {
            $vote = new Vote($jokepost, $user);
            $jokepost->voteUp();
        }

        if ($vote->getDown()) {
            $jokepost->voteDownToUp();
        }

        $vote->voteUp();

        $em->persist($jokepost);
        $em->persist($vote);
        $em->flush();

        return new JsonResponse($this->serializer->serialize($jokepost, 'json'), 200);
    }

    /**
     * Method that permit to unlike a post a Vote via the API.
     *
     * @param $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function unlikeApiAction(Request $request, $id)
    {
        $key = $this->serializer->deserialize($request->getContent(), APIKey::class, 'json');
        $em = $this->getDoctrine()->getManager();
        $APIKey = $em->getRepository('AppBundle:APIKey')->findOneByHash($key->getHash());

        if (!$APIKey) {
            throw new BadCredentialsException('Need a valid APIKey');
        }

        if (!$APIKey->isValid()) {
            throw new BadCredentialsException('Token expired');
        }
        $user = $APIKey->getUser();

        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $repositoryVote = $this->getDoctrine()->getRepository('AppBundle:Vote');

        $jokepost = $repository->findOneById($id);

        if (!$jokepost) {
            return new JsonResponse(['message' => 'This jokepost does not exist'], 404);
        }

        $vote = $repositoryVote->findOneBy(['jokepost' => $jokepost, 'user' => $user]);

        if (!$vote) {
            $vote = new Vote($jokepost, $user);
            $jokepost->voteDown();
        }

        if ($vote->getUp()) {
            $jokepost->voteUpToDown();
        }

        $vote->voteDown();

        $em->persist($jokepost);
        $em->persist($vote);
        $em->flush();

        return new JsonResponse($this->serializer->serialize($jokepost, 'json'), 200);
    }
}
