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
     * Add a new controller to a jokepost by the $id.
     *
     * @param $request
     * @param $id
     *
     * @return JsonResponse
     */
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
            return new JsonResponse(['message' => 'This jokepost does not exist'], 404);
        }

        $comment->setUser($user);
        $comment->setJokepost($jokepost);

        $em->persist($comment);
        $em->flush();

        return new JsonResponse($this->serializer->serialize($comment, 'json'), 200);
    }
}
