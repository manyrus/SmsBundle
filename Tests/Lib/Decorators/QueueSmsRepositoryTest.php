<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 02.03.14
 * Time: 23:17
 */

namespace Manyrus\SmsBundle\Tests\Lib\Decorators;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\Base\ISmsRepository;
use Manyrus\SmsBundle\Lib\Decorators\QueueSmsRepository;
use Manyrus\SmsBundle\Lib\Status;

class QueueSmsRepositoryTest extends DecoratorsTest{



    public function testSend() {
        $sms = $this->getSmsMessage();

        $mock = $this->getMockForAbstractClass('Manyrus\SmsBundle\Lib\Base\ISmsRepository');;

        $mock->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf('Manyrus\SmsBundle\Entity\SmsMessage'))
            ->will($this->returnCallback(
                function(SmsMessage $message) {
                    $message->setMessageId('0-0');
                    $message->setStatus(Status::IN_PROCESS);
                }
            ));

        $queue = new QueueSmsRepository($mock);

        $queue->send($sms);
        $this->assertEquals($sms->getStatus(), Status::IN_QUEUE);

        $queue->send($sms);
        $this->assertEquals($sms->getStatus(), Status::IN_PROCESS);
        $this->assertEquals($sms->getMessageId(), '0-0');
    }

    public function testUpdateStatus() {
        $sms = $this->getSmsMessage();
        $sms->setStatus(Status::IN_PROCESS);

        $mock = $this->getMockForAbstractClass('Manyrus\SmsBundle\Lib\Base\ISmsRepository');;

        $mock->expects($this->once())
            ->method('updateStatus')
            ->with($this->isInstanceOf('Manyrus\SmsBundle\Entity\SmsMessage'))
            ->will($this->returnCallback(
                    function(SmsMessage $message) {
                        $message->setStatus(Status::DELIVERED);
                    }
                )
            );
        $class = $this->getClassName();
        /** @var ISmsRepository $test */
        $test= new $class($mock);

        $test->updateStatus($sms);
        $this->assertEquals(Status::DELIVERED, $sms->getStatus());
    }

    public function testUpdateCost() {
        $sms = $this->getSmsMessage();
        $sms->setStatus(Status::IN_PROCESS);

        $mock = $this->getMockForAbstractClass('Manyrus\SmsBundle\Lib\Base\ISmsRepository');;

        $mock->expects($this->once())
            ->method('updateCost')
            ->with($this->isInstanceOf('Manyrus\SmsBundle\Entity\SmsMessage'))
            ->will($this->returnCallback(
                    function(SmsMessage $message) {
                        $message->setCost(0,5);
                    }
                )
            );
        $class = $this->getClassName();
        /** @var ISmsRepository $test */
        $test = new $class($mock);

        $test->updateCost($sms);
        $this->assertEquals($sms->getCost(), 0,5);
    }


    protected function getClassName()
    {
        return 'Manyrus\SmsBundle\Lib\Decorators\QueueSmsRepository';
    }
}