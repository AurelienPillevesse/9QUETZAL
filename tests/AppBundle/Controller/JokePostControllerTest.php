<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Controller\JokePostController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JokePostControllerTest extends WebTestCase
{
    public function testLikeAction()
    {
        $jokepost = new JokePostController();
        $result = $jokepost->likeAction();
        $this->assertEquals(true, $client->getResponse()->isRedirect('/jokepost-one'));
    }
}
