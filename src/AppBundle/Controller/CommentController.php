<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\APIKey;
use AppBundle\Entity\Comment;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\Serializer;

class CommentController extends Controller
{
    private $serializer;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->serializer = $this->get('app.serializer.default');
    }

    public function newApiAction(Request $request, $id)
    {
        $key = $this->serializer->deserialize($request->getContent(), APIKey::class, 'json');
        $comment = $this->serializer->deserialize($request->getContent(), Comment::class, 'json');

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
        $jokepost = $jokepostRepo->findOneById($id);

        if (!$jokepost) {
            //jokepost doesn't exist
        }

        $comment->setUser($user);
        $comment->setJokepost($jokepost);

        $em->persist($comment);
        $em->flush();

        return new JsonResponse($this->serializer->serialize($comment, 'json'), 200);
    }
}
