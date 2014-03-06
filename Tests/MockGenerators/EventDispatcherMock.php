<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 16:51
 */

namespace Manyrus\SmsBundle\Tests\MockGenerators;


use Manyrus\SmsBundle\Lib\Event\MergeEvent;
use Manyrus\SmsBundle\Lib\Event\MergeEvents;
use Manyrus\SmsBundle\Lib\Event\SmsEvent;
use Manyrus\SmsBundle\Lib\Event\SmsEvents;
use Manyrus\SmsBundle\Tests\Lib\Decorators\EventSmsRepositoryTest;

class EventDispatcherMock {

    /**
     * @var EventSmsRepositoryTest
     */
    private $test;

    function __construct($eventSmsRepositoryTest)
    {
        $this->test = $eventSmsRepositoryTest;
    }

    public function onMergeEvent($expects) {
        $sms = $this->test->smsMessage;
        $self = $this->test;

        $this->test->eventDispatcher
            ->expects($expects)
            ->method('dispatch')
            ->with($this->test->equalTo(MergeEvents::ON_MERGE)
                , $this->test->isInstanceOf('Manyrus\SmsBundle\Lib\Event\MergeEvent'))
            ->will($this->test->returnCallback(
                function($name,MergeEvent $mergeEvent) use($sms, $self){
                    $self->assertEquals($mergeEvent->getMessage(), $sms);
                    $self->assertEquals(SmsRepositoryMock::API_TYPE, $mergeEvent->getApiType());
                }
            ))
        ;
    }

    public function messageChangedEvent($expects) {
        $sms = $this->test->smsMessage;
        $self = $this->test;

        $this->test->eventDispatcher
            ->expects($expects)
            ->method('dispatch')
            ->with($this->test->equalTo(SmsEvents::SMS_CHANGED)
                , $this->test->isInstanceOf('Manyrus\SmsBundle\Lib\Event\SmsEvent'))
            ->will($this->test->returnCallback(
                function($name,SmsEvent $smsEvent) use($sms, $self){
                    $self->assertEquals($smsEvent->getMessage(), $sms);
                }
            ))
        ;
    }

    public function preSendEvent($expects) {
        $this->sendEvent($expects, SmsEvents::PRE_SEND);

    }

    public function postSendEvent($expects) {
        $this->sendEvent($expects, SmsEvents::POST_SEND);
    }

    private function sendEvent($expects, $with) {
        $sms = $this->test->smsMessage;
        $self = $this->test;

        $this->test->eventDispatcher
            ->expects($expects)
            ->method('dispatch')
            ->with($this->test->equalTo($with)
                , $this->test->isInstanceOf('Manyrus\SmsBundle\Lib\Event\SmsEvent'))
            ->will($this->test->returnCallback(function($name, SmsEvent $event) use ($sms, $self){
                $self->assertEquals($event->getMessage(), $sms);
            }))
        ;
    }



    public function errorEvent($expects) {
        $self = $this->test;
        $this->test->eventDispatcher
            ->expects($expects)
            ->method('dispatch')
            ->with($this->test->equalTo(SmsEvents::ERROR_SEND)
                , $this->test->isInstanceOf('Manyrus\SmsBundle\Lib\Event\SmsEvent'))
            ->will($this->test->returnCallback(function($name,SmsEvent $event) use ($self){$self->assertEquals(SmsRepositoryMock::API_ERROR, $event->getException()->getError());}))
        ;
    }
} 