<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Controller\JokePostController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JokePostControllerTest extends WebTestCase
{
<<<<<<< HEAD
  public function testLikeAction()
  {
      $jokepost = new JokePostController();
      $result = $jokepost->likeAction();
      $this->assertEquals(true,$client->getResponse()->isRedirect('/jokepost-one'));
  }

  public function setContainerTest()
  {

  }

  public function newActionTest()
  {

  }

  public function allActionTest()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');
    $this->assertGreaterThan(0, $crawler->filter('div.postelement')->count());
  }

  public function listApiActionTest()
  {

  }

  public function oneActionTest()
  {

  }

  public function likeActionTest()
  {

  }

  public function unlikeActionTest()
  {

  }
=======
    public function testLikeAction()
    {
        $jokepost = new JokePostController();
        $result = $jokepost->likeAction();
        $this->assertEquals(true, $client->getResponse()->isRedirect('/jokepost-one'));
    }
>>>>>>> d885bef8b2398d79c1827b2319ce2d64bc19f5d6
}
