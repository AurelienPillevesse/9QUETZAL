<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;

class UserNormalizerTest extends \PHPUnit\Framework\TestCase
{
    public function testNormalize()
    {
        $userMock = $this->createMock(User::class);
        $userMock->expects($this->once())->method('getId')->will($this->returnValue('1'));
        $userMock->expects($this->once())->method('getUsername')->will($this->returnValue('Test'));

        $userNormalized = [
            'id' => '1',
            'username' => 'Test',
        ];
        $this->userNormalizer = new UserNormalizer();
        $user = $this->userNormalizer->normalize($userMock);

        $this->assertEquals('Test', $user['username']);
        $this->assertEquals($userNormalized, $user);
    }
}
