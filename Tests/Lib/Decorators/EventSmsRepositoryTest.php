<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 0:16
 */

namespace Manyrus\SmsBundle\Tests\Lib\Decorators;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\Decorators\EventSmsRepository;
use Manyrus\SmsBundle\Lib\Event\MergeEvent;
use Manyrus\SmsBundle\Lib\Event\MergeEvents;
use Manyrus\SmsBundle\Lib\Event\SmsEvent;
use Manyrus\SmsBundle\Lib\Event\SmsEvents;
use Manyrus\SmsBundle\Lib\SmsException;
use Manyrus\SmsBundle\Lib\Status;

class EventSmsRepositoryTest extends DecoratorsTest{


    public function testSend() {
        $obj = $this;//php 5.3 support

        $sms = $this->getSmsMessage();
        $smsRepository = $this->getMockForAbstractClass('Manyrus\SmsBundle\Lib\Base\ISmsRepository');

        $smsRepository->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf('Manyrus\SmsBundle\Entity\SmsMessage'))
            ->will($this->returnCallback(
                function(SmsMessage $m) {
                    $m->setStatus(Status::IN_PROCESS);
                    $m->setMessageId('0-0');
                }
            ));

        $smsRepository->expects($this->once())
            ->method('getApiType')
            ->will($this->returnValue(ApiType::EPOCHTA_API)
            );

        $eventDispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcher');

        $eventDispatcher
            ->expects($this->at(0))
            ->method('dispatch')
            ->with($this->equalTo(MergeEvents::ON_MERGE)
                , $this->isInstanceOf('Manyrus\SmsBundle\Lib\Event\MergeEvent'))
            ->will($this->returnCallback(
                function($name,MergeEvent $mergeEvent) use($sms, $obj){
                    $obj->assertEquals($mergeEvent->getMessage(), $sms);
                    $obj->assertEquals(ApiType::EPOCHTA_API, $mergeEvent->getApiType());
                }
            ))
        ;

        $eventDispatcher
            ->expects($this->at(1))
            ->method('dispatch')
            ->with($this->equalTo(SmsEvents::PRE_SEND)
            , $this->isInstanceOf('Manyrus\SmsBundle\Lib\Event\SmsEvent'))
            ->will($this->returnCallback(function($name, SmsEvent $event) use ($sms, $obj){
                $obj->assertEquals($event->getMessage(), $sms);
            }))
        ;

        $eventDispatcher
            ->expects($this->at(2))
            ->method('dispatch')
            ->with($this->equalTo(SmsEvents::POST_SEND)
                , $this->isInstanceOf('Manyrus\SmsBundle\Lib\Event\SmsEvent'))
            ->will($this->returnCallback(function($name, SmsEvent $event) use ($sms, $obj){
                $obj->assertEquals($event->getMessage(), $sms);
            }));



        $eventRepository = new EventSmsRepository($smsRepository);
        $eventRepository->setEventDispatcher($eventDispatcher);

        $eventRepository->send($sms);

        $this->assertEquals(Status::IN_PROCESS, $sms->getStatus());
        $this->assertEquals('0-0', $sms->getMessageId());
    }

    public function testErrorSend() {
        $obj = $this;
        $sms = $this->getSmsMessage();
        $smsRepository = $this->getMockForAbstractClass('Manyrus\SmsBundle\Lib\Base\ISmsRepository');

        $eventDispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcher');

        $smsRepository->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf('Manyrus\SmsBundle\Entity\SmsMessage'))
            ->will($this->returnCallback(
                function(SmsMessage $m) {
                    throw new SmsException(ApiErrors::LOW_BALANCE, '403');
                }
            ));

        $eventDispatcher
            ->expects($this->at(0))
            ->method('dispatch')
            ->with($this->equalTo(MergeEvents::ON_MERGE)
                , $this->isInstanceOf('Manyrus\SmsBundle\Lib\Event\MergeEvent'))
        ;


        $eventDispatcher
            ->expects($this->at(1))
            ->method('dispatch')
            ->with($this->equalTo(SmsEvents::PRE_SEND)
                , $this->isInstanceOf('Manyrus\SmsBundle\Lib\Event\SmsEvent'));

        $eventDispatcher
            ->expects($this->at(2))
            ->method('dispatch')
            ->with($this->equalTo(SmsEvents::ERROR_SEND)
                , $this->isInstanceOf('Manyrus\SmsBundle\Lib\Event\SmsEvent'))
            ->will($this->returnCallback(function($name,SmsEvent $event) use ($obj){$obj->assertNotNull($event->getException());}))
        ;

        $this->setExpectedException('Manyrus\SmsBundle\Lib\SmsException');

        $eventRepository = new EventSmsRepository($smsRepository);
        $eventRepository->setEventDispatcher($eventDispatcher);

        $eventRepository->send($sms);
    }

    public function testUpdateCost() {
        $obj = $this;

        $sms = $this->getSmsMessage();
        $sms->setCost(0);

        $eventDispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcher');

        $smsRepository = $this->getMockForAbstractClass('Manyrus\SmsBundle\Lib\Base\ISmsRepository');

        $smsRepository->expects($this->exactly(2))
            ->method('updateCost')
            ->with($this->isInstanceOf('Manyrus\SmsBundle\Entity\SmsMessage'))
            ->will($this->returnCallback(
                function(SmsMessage $m) {
                    $m->setCost(5);
                }
            ));

        $smsRepository->expects($this->exactly(2))
            ->method('getApiType')
            ->will($this->returnValue(ApiType::EPOCHTA_API)
            );

        $eventDispatcher
            ->expects($this->at(0))
            ->method('dispatch')
            ->with($this->equalTo(MergeEvents::ON_MERGE)
                , $this->isInstanceOf('Manyrus\SmsBundle\Lib\Event\MergeEvent'))
            ->will($this->returnCallback(
                function($name,MergeEvent $mergeEvent) use($sms, $obj){
                    $obj->assertEquals($mergeEvent->getMessage(), $sms);
                    $obj->assertEquals(ApiType::EPOCHTA_API, $mergeEvent->getApiType());
                }
            ))
        ;

        $eventDispatcher
            ->expects($this->at(1))
            ->method('dispatch')
            ->with($this->equalTo(SmsEvents::SMS_CHANGED)
                , $this->isInstanceOf('Manyrus\SmsBundle\Lib\Event\SmsEvent'))
            ->will($this->returnCallback(
                function($name,SmsEvent $smsEvent) use($sms, $obj){
                    $obj->assertEquals($smsEvent->getMessage(), $sms);
                }
            ))
        ;

        $eventRepository = new EventSmsRepository($smsRepository);
        $eventRepository->setEventDispatcher($eventDispatcher);

        $eventRepository->updateCost($sms);

        $this->assertEquals(5, $sms->getCost());

        $eventDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(MergeEvents::ON_MERGE)
                , $this->isInstanceOf('Manyrus\SmsBundle\Lib\Event\MergeEvent'));

        $eventRepository->updateCost($sms);

        $this->assertEquals(5, $sms->getCost());
    }

    public function testUpdateStatus() {
        $obj = $this;

        $sms = $this->getSmsMessage();
        $sms->setStatus(Status::IN_PROCESS);

        $eventDispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcher');

        $smsRepository = $this->getMockForAbstractClass('Manyrus\SmsBundle\Lib\Base\ISmsRepository');


        $smsRepository->expects($this->exactly(2))
            ->method('updateStatus')
            ->with($this->isInstanceOf('Manyrus\SmsBundle\Entity\SmsMessage'))
            ->will($this->returnCallback(
                function(SmsMessage $m) {
                    $m->setStatus(Status::DELIVERED);
                }
            ));

        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(SmsEvents::SMS_CHANGED)
                , $this->isInstanceOf('Manyrus\SmsBundle\Lib\Event\SmsEvent'))
            ->will($this->returnCallback(
                function($name,SmsEvent $smsEvent) use($sms, $obj){
                    $obj->assertEquals($smsEvent->getMessage(), $sms);
                }
            ))
        ;

        $eventRepository = new EventSmsRepository($smsRepository);
        $eventRepository->setEventDispatcher($eventDispatcher);

        $eventRepository->updateStatus($sms);
        $this->assertEquals(Status::DELIVERED, $sms->getStatus());

        $eventRepository->updateStatus($sms);//the second calling
        $this->assertEquals(Status::DELIVERED, $sms->getStatus());
    }

    protected function getClassName()
    {
        return 'Manyrus\SmsBundle\Lib\Decorators\EventSmsRepository';
    }
}