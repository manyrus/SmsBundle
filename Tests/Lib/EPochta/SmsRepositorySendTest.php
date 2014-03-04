<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 04.03.14
 * Time: 12:53
 */

namespace Manyrus\SmsBundle\Tests\Lib\EPochta;


use Manyrus\SmsBundle\Lib\Status;

class SmsRepositorySendTest extends BaseTest{
    public function setUp() {
        parent::setUp();

        $this->smsMessage->setFrom('79216778055');
        $this->smsMessage->setMessage('hello!');
        $this->smsMessage->setTo('1111');
    }

    public function testSend() {
        $this->createBuzzSubmitWithReturn($this->returnValue(
            '{"result":{"id":"22-21"}}'
        ), $this->getArgsSendArray());

        $this->repo->send($this->smsMessage);

        $this->assertEquals("22-21", $this->smsMessage->getMessageId());
        $this->assertEquals(Status::IN_PROCESS, $this->smsMessage->getStatus());
    }
} 