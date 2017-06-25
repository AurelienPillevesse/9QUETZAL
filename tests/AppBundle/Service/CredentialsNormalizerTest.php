<?php

namespace AppBundle\Service;

use AppBundle\Entity\Credentials;

class CredentialsNormalizerTest extends \PHPUnit\Framework\TestCase
{
    public function testNormalize()
    {
        $credentialsMock = $this->createMock(Credentials::class);
        $credentialsMock->expects($this->once())->method('getUsername')->will($this->returnValue('Test'));

        $credentialsNormalized = [
            'username' => 'Test',
        ];
        $this->credentialsNormalized = new CredentialsNormalizer();
        $credentials = $this->credentialsNormalized->normalize($credentialsMock);

        $this->assertEquals('Test', $credentials['username']);
        $this->assertEquals($credentialsNormalized, $credentials);
    }
}
