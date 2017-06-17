<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\APIKey;
use AppBundle\Entity\Comment;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    public function addApiAction($idJokePost, Request $request)
    {
        $receivedData = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $APIKey = $em->getRepository('AppBundle:APIKey')->findOneByHash($receivedData['token']);

        if (!$APIKey) {
            throw new BadCredentialsException();
        }

        $jokepost = $em->getRepository('AppBundle:JokePost')->findOneById($idJokePost);

        $comment = new Comment();
        $comment->setJokepost($jokepost);
        $comment->setUser($APIKey->getUser());
        $comment->setContent($receivedData['content']);

        $em->persist($comment);
        $em->flush();

        return new Response('haha');
    }
}
