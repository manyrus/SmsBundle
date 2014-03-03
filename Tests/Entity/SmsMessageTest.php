<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 02.03.14
 * Time: 9:24
 */

namespace Manyrus\SmsBundle\Tests\Entity;


use Manyrus\SmsBundle\Entity\SmsError;
use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\Status;

class SmsMessageTest extends \PHPUnit_Framework_TestCase{
    const ID = 9;

    public function testStatus() {
        $sms = $this->getSmsMessage();
        $this->assertNull($sms->getStatus());

        $sms->setStatus(Status::DELIVERED);
        $this->assertEquals(Status::DELIVERED, $sms->getStatus());

    }

    public function testError() {
        $sms = $this->getSmsMessage();
        $error = $this->getSmsError();

        $sms->setError($error);

        $this->assertEquals(Status::ERROR, $sms->getStatus());
        $this->assertEquals($error, $sms->getError());
    }

    public function testMessage() {
        $sms = $this->getSmsMessage();
        $this->assertNull($sms->getMessage());

        $sms->setMessage('helllo!');
        $this->assertEquals('helllo!', $sms->getMessage());
    }

    public function testTo() {
        $sms = $this->getSmsMessage();
        $this->assertNull($sms->getTo());

        $sms->setTo('79216778055');
        $this->assertEquals('79216778055', $sms->getTo());
    }

    public function testPrePersist() {
        $sms = $this->getSmsMessage();
        $this->assertNull($sms->getCreated());

        $sms->prePersist();

        $dateTime = new \DateTime();
        $this->assertEquals($dateTime->format('Y-m-d'), $sms->getCreated()->format('Y-m-d'));
    }

    public function testFrom() {
        $sms = $this->getSmsMessage();
        $this->assertNull($sms->getFrom());

        $sms->setFrom('79216778055');
        $this->assertEquals('79216778055', $sms->getFrom());
    }

    public function testApiType() {
        $sms = $this->getSmsMessage();
        $this->assertNull($sms->getApiType());

        $sms->setApiType(ApiType::EPOCHTA_API);
        $this->assertEquals(ApiType::EPOCHTA_API, $sms->getApiType());
    }

    public function testMessageId() {
        $sms = $this->getSmsMessage();
        $this->assertNull($sms->getMessageId());


        $sms->setMessageId('000--12');
        $this->assertEquals('000--12', $sms->getMessageId());
    }


    /**
     * @return SmsMessage
     */
    private function getSmsMessage() {
        return $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage');
    }

    /**
     * @return SmsError
     */
    private function getSmsError() {
        return $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsError');
    }
}
