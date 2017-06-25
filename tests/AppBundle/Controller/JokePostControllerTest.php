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

  public function testsetContainer()
  {

  }

  public function testnewAction()
  {

  }

  public function testallAction()
  {
    $client = static::createClient();
    $crawler = $client->request('GET', '/');
    $this->assertGreaterThan(0, $crawler->filter('div.postelement')->count());
  }

  public function testlistApiAction()
  {

  }

  public function testoneAction()
  {

  }

  public function testunlikeAction()
  {

  }
}
