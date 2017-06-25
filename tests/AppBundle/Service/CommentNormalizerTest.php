<?php

namespace AppBundle\Service;

use AppBundle\Entity\Comment;

class CommentNormalizerTest extends \PHPUnit\Framework\TestCase
{
    public function testNormalize()
    {
        $commentMock = $this->createMock(Comment::class);
        $commentMock->expects($this->once())->method('getContent')->will($this->returnValue('This is the test comment'));

        $userNormalized = [
            'id' => '1',
            'username' => 'test',
        ];
        $userNormalizerMock = $this->createMock(UserNormalizer::class);
        $userNormalizerMock->expects($this->once())->method('normalize')->will($this->returnValue($userNormalized));

        $this->commentNormalizer = new CommentNormalizer();
        $this->commentNormalizer->setNormalizer($userNormalizerMock);
        $comment = $this->commentNormalizer->normalize($commentMock);

        $this->assertEquals('This is the test comment', $comment['content']);
        $this->assertEquals($userNormalized, $comment['user']);
    }
}
