<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\JokePost;
use AppBundle\Entity\Vote;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class VoteController extends Controller
{
    private $serializer;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->serializer = $this->get('app.serializer.default');
    }

    public function likeApiAction(Request $request, $id)
    {
        $receivedData = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $APIKey = $em->getRepository('AppBundle:APIKey')->findOneByHash($receivedData['token']);

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

    public function unlikeApiAction(Request $request, $id)
    {
        $receivedData = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $APIKey = $em->getRepository('AppBundle:APIKey')->findOneByHash($receivedData['token']);

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
