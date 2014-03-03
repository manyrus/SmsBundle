<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 18:27
 */

namespace Manyrus\SmsBundle\Tests\Lib\Event;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\Event\SmsEvent;
use Manyrus\SmsBundle\Lib\SmsException;

class SmsEventTest extends \PHPUnit_Framework_TestCase{
    /**
     * @var SmsMessage
     */
    private $smsMessage;

    public function setUp() {
        $this->smsMessage = $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage');
    }

    public function testMessage() {
        $smsEvent = new SmsEvent($this->smsMessage);
        $this->assertEquals($this->smsMessage, $smsEvent->getMessage());
        $this->assertNull($smsEvent->getException());
    }

    public function testMessageAndException() {
        $exception = new SmsException(ApiErrors::AUTH_ERROR, ApiErrors::AUTH_ERROR);
        $smsEvent = new SmsEvent($this->smsMessage, $exception);
        $this->assertEquals($this->smsMessage, $smsEvent->getMessage());
        $this->assertEquals($exception, $smsEvent->getException());
    }
} 