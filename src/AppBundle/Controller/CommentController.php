<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\APIKey;
use AppBundle\Entity\Comment;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class CommentController extends Controller
{
    public function addApiAction(Request $request, $idJokePost)
    {
        $receivedData = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $APIKey = $em->getRepository('AppBundle:APIKey')->findOneByHash($receivedData['token']);

        if (!$APIKey) {
            throw new BadCredentialsException("Need the user's token");
        }

        $repository = $this->getDoctrine()->getRepository('AppBundle:JokePost');
        $jokepost = $repository->findOneById($idJokePost);

        $comment = new Comment();
        $comment->setJokepost($jokepost);
        $comment->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        return new JsonResponse($this->serializer->serialize($jokepost, 'json'), 200);
    }
}
