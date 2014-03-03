<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 18:22
 */

namespace Manyrus\SmsBundle\Tests\Lib\Event;


use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\Event\MergeEvent;
use Manyrus\TestBundle\Entity\SmsMessage;

class MergeEventTest extends \PHPUnit_Framework_TestCase{
    const API_TYPE = ApiType::EPOCHTA_API;

    /**
     * @var MergeEvent
     */
    private $mergeEvent;

    /**
     * @var SmsMessage
     */
    private $smsMessage;

    public function setUp() {
        $this->smsMessage = $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage');
        $this->mergeEvent = new MergeEvent(self::API_TYPE, $this->smsMessage);
    }

    public function testGetters() {
        $this->assertEquals(self::API_TYPE, $this->mergeEvent->getApiType());
        $this->assertEquals($this->smsMessage, $this->mergeEvent->getMessage());
    }
} 