<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserController extends Controller
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
     * Method that register an User via the API.
     *
     * @param $request
     *
     * @return JsonResponse
     */
    public function registerApiAction(Request $request)
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $userManager->updateUser($user);

        return new JsonResponse($this->serializer->serialize($user, 'json'), 200);
    }
}
