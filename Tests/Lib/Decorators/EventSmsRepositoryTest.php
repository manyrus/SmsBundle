<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 0:16
 */

namespace Manyrus\SmsBundle\Tests\Lib\Decorators;


use Manyrus\SmsBundle\Lib\Decorators\EventSmsRepository;
use Manyrus\SmsBundle\Lib\Status;
use Manyrus\SmsBundle\Tests\MockGenerators\EventDispatcherMock;
use Manyrus\SmsBundle\Tests\MockGenerators\SmsRepositoryMock;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventSmsRepositoryTest extends DecoratorsTest{
    /**
     * @var EventDispatcher
     */
    public $eventDispatcher;

    /**
     * @var EventSmsRepository
     */
    public $eventRepository;

    /**
     * @var EventDispatcherMock
     */
    private $eventDispatcherGen;

    protected function setUp() {
        parent::setUp();
        $this->eventDispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcher');

        $this->eventRepository = new EventSmsRepository($this->smsRepository);
        $this->eventRepository->setEventDispatcher($this->eventDispatcher);

        $this->eventDispatcherGen = new EventDispatcherMock($this);
    }


    public function testSend() {
        $this->smsRepositoryGen->sendMethod($this->once());
        $this->smsRepositoryGen->apiTypeMethod($this->once());

        $this->eventDispatcherGen->onMergeEvent($this->at(0));
        $this->eventDispatcherGen->preSendEvent($this->at(1));
        $this->eventDispatcherGen->postSendEvent($this->at(2));

        $this->eventRepository->send($this->smsMessage);

        $this->assertEquals(SmsRepositoryMock::SEND_STATUS, $this->smsMessage->getStatus());
        $this->assertEquals(SmsRepositoryMock::MESSAGE_ID, $this->smsMessage->getMessageId());
    }

    public function testErrorSend() {
        $this->smsRepositoryGen->sendMethod($this->once(), true);
        $this->smsRepositoryGen->apiTypeMethod($this->once());

        $this->eventDispatcherGen->onMergeEvent($this->at(0));
        $this->eventDispatcherGen->preSendEvent($this->at(1));
        $this->eventDispatcherGen->errorEvent($this->at(2));

        $this->setExpectedException('Manyrus\SmsBundle\Lib\SmsException');

        $this->eventRepository->send($this->smsMessage);
    }

    public function testUpdateCost() {
        $this->smsMessage->setCost(0);

        $this->smsRepositoryGen->updateCost($this->exactly(2));
        $this->smsRepositoryGen->apiTypeMethod($this->exactly(2));

        $this->eventDispatcherGen->onMergeEvent($this->at(0));
        $this->eventDispatcherGen->messageChangedEvent($this->at(1));

        $this->eventRepository->updateCost($this->smsMessage);
        $this->assertEquals(SmsRepositoryMock::COST, $this->smsMessage->getCost());

        $this->eventDispatcherGen->onMergeEvent($this->once());

        $this->eventRepository->updateCost($this->smsMessage);
        $this->assertEquals(SmsRepositoryMock::COST, $this->smsMessage->getCost());
    }

    public function testUpdateStatus() {
        $this->smsMessage->setStatus(Status::IN_PROCESS);

        $this->smsRepositoryGen->updateStatus($this->exactly(2));
        $this->eventDispatcherGen->messageChangedEvent($this->once());

        $this->eventRepository->updateStatus($this->smsMessage);
        $this->assertEquals(SmsRepositoryMock::UPDATE_STATUS, $this->smsMessage->getStatus());

        $this->eventRepository->updateStatus($this->smsMessage);//the second calling
        $this->assertEquals(SmsRepositoryMock::UPDATE_STATUS, $this->smsMessage->getStatus());
    }


    protected function getClassName()
    {
        return 'Manyrus\SmsBundle\Lib\Decorators\EventSmsRepository';
    }
}