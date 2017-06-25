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
}
