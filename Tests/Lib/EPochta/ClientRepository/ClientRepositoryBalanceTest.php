<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 06.03.14
 * Time: 21:26
 */

namespace Manyrus\SmsBundle\Tests\Lib\EPochta\ClientRepository;


use Buzz\Message\RequestInterface;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\SmsException;
use Manyrus\TestBundle\Entity\SmsMessage;

class ClientRepositoryBalanceTest extends BaseClientRepositoryRepositoryTest{


    public function testUpdateBalance() {
        $balance = 123.45;
        $this->createBuzzSubmitWithReturn($this->returnValue('{"result":{"balance_currency":123.45,"currency":"RUB"}}'), $this->getSubmitExpectedArgs());

        $this->clientRepository->updateBalance($this->client);

        $this->assertSame($balance, $this->client->getBalance());
    }

    protected function callTestMethod()
    {
        $this->clientRepository->updateBalance($this->client);
    }

    protected function getSubmitExpectedArgs()
    {
        return array(
            'http://atompark.com/api/sms/3.0/getUserBalance',
            array(
                'version' => '3.0',
                'currency' => 'RUB',
                'action' => 'getUserBalance',
                'key' => '',
                'test' => 0,
                'sum' => 'ed5d9121b1731ce243705d3bb657524d'
            ), RequestInterface::METHOD_GET, array()
        );
    }
}