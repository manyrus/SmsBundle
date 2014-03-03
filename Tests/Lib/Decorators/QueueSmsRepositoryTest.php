<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 02.03.14
 * Time: 23:17
 */

namespace Manyrus\SmsBundle\Tests\Lib\Decorators;


use Manyrus\SmsBundle\Lib\Decorators\QueueSmsRepository;
use Manyrus\SmsBundle\Lib\Status;
use Manyrus\SmsBundle\Tests\MockGenerators\SmsRepositoryMock;

class QueueSmsRepositoryTest extends DecoratorsTest{

    /**
     * @var QueueSmsRepository
     */
    private $queueRepository;

    protected function setUp() {
        parent::setUp();
        $this->queueRepository = new QueueSmsRepository($this->smsRepository);
    }

    public function testSend() {
        $this->smsRepositoryGen->sendMethod($this->once());

        $this->queueRepository->send($this->smsMessage);
        $this->assertEquals($this->smsMessage->getStatus(), Status::IN_QUEUE);

        $this->queueRepository->send($this->smsMessage);
        $this->assertEquals($this->smsMessage->getStatus(), SmsRepositoryMock::SEND_STATUS);
        $this->assertEquals($this->smsMessage->getMessageId(), SmsRepositoryMock::MESSAGE_ID);
    }

    public function testUpdateStatus() {
        $this->smsRepositoryGen->updateStatus($this->once());
        $this->queueRepository->updateStatus($this->smsMessage);
        $this->assertEquals($this->smsMessage->getStatus(), SmsRepositoryMock::UPDATE_STATUS);
    }

    public function testUpdateCost() {
        $this->smsRepositoryGen->updateCost($this->once());
        $this->queueRepository->updateCost($this->smsMessage);
        $this->assertEquals($this->smsMessage->getCost(), SmsRepositoryMock::COST);
    }


    protected function getClassName()
    {
        return 'Manyrus\SmsBundle\Lib\Decorators\QueueSmsRepository';
    }
}