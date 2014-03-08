<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 07.03.14
 * Time: 17:29
 */

namespace Manyrus\SmsBundle\Tests\Lib\EPochta\SmsRepository;


use Buzz\Message\RequestInterface;
use Manyrus\SmsBundle\Lib\EPochta\BaseEPochtaRepository;
use Manyrus\SmsBundle\Lib\EPochta\SmsRepository;

class SmsRepositoryUpdateCostTest extends BaseSmsRepositoryRepositoryTest{

    public function testUpdateCost() {
        $cost=0.5559;
        $this->createBuzzSubmitWithReturn($this->returnValue(
            '{"result":{"price":'.$cost.'}}'
        ), $this->getSubmitExpectedArgs());
        $this->repo->updateCost($this->smsMessage);

        $this->assertEquals($cost, $this->smsMessage->getCost());
    }

    protected function callTestMethod()
    {
        $this->repo->updateCost($this->smsMessage);
    }

    protected function getSubmitExpectedArgs()
    {
        return array(sprintf("http://atompark.com/api/sms/%s/%s", BaseEPochtaRepository::VERSION, SmsRepository::GET_PRICE), array(
            'sender' => $this->smsMessage->getFrom(),
            'text' => $this->smsMessage->getMessage(),
            'phones' => "[[\"{$this->smsMessage->getTo()}\"]]",
            'version' => BaseEPochtaRepository::VERSION,
            'action' => SmsRepository::GET_PRICE,
            'key' => '',
            'test' => (int) $this->config->getIsTest(),
            'sum' => '43041377e03997bc041be918bf30fd26'
        ), RequestInterface::METHOD_GET, array());
    }
}