<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CredentialsType;
use AppBundle\Entity\Credentials;
use AppBundle\Entity\APIKey;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class APIKeyController extends Controller
{
    public function loginApiAction(Request $request)
    {
        $receivedCredentials = json_decode($request->getContent(), true);
        $credentials = new Credentials();
        $form = $this->createForm(CredentialsType::class, $credentials);
        $form->submit($receivedCredentials);

        if (!$receivedCredentials['username']) {
            throw new BadCredentialsException('No username field in request');
        }
        if (!$receivedCredentials['password']) {
            throw new BadCredentialsException('No password field in request');
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneByUsername($credentials->getUsername());

        if (!$user) {
            throw new BadCredentialsException();
        }

        $encoder = $this->get('security.password_encoder');
        $isPasswordValid = $encoder->isPasswordValid($user, $credentials->getPassword());

        if ($isPasswordValid) {
            $APIKeyUser = new APIKey();
            $APIKeyUser->setUser($user);

            $em->persist($APIKeyUser);

            $AllAPIKeys = $em->getRepository('AppBundle:APIKey')->findByUser($user);
            foreach ($AllAPIKeys as $key) {
                $key->setLifetime(0);
                $em->persist($key);
            }
            $em->flush();

            $encoders = new JsonEncoder();
            $normalizers = new ObjectNormalizer();

            $normalizers->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });

            $serializer = new Serializer(array($normalizers), array($encoders));
            $jsonContent = $serializer->serialize($APIKeyUser, 'json');

            return new JsonResponse($jsonContent);
        }
        throw new BadCredentialsException();
    }
}
