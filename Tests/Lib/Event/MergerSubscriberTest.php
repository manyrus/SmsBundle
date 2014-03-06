<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 19:22
 */

namespace Manyrus\SmsBundle\Tests\Lib\Event;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\Base\BaseConfig;
use Manyrus\SmsBundle\Lib\Event\MergeEvent;
use Manyrus\SmsBundle\Lib\Event\MergeEvents;
use Manyrus\SmsBundle\Lib\Event\MergerSubscriber;

class MergerSubscriberTest extends \PHPUnit_Framework_TestCase{
    const CONFIG_NUM = '8911';

    const API_TYPE = ApiType::EPOCHTA_API;
    /**
     * @var BaseConfig
     */
    protected $baseConfig;

    /**
     * @var MergerSubscriber
     */
    protected $mergerSubscriber;

    /**
     * @var SmsMessage
     */
    private $smsMessage;

    protected function setUp() {
        $this->baseConfig = $this->getMockForAbstractClass('Manyrus\SmsBundle\Lib\Base\BaseConfig');
        $this->smsMessage = $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage');
        $this->mergerSubscriber = new MergerSubscriber($this->baseConfig);
    }

    public  function testMergeConfigFrom() {
        $this->baseConfig->setFrom(self::CONFIG_NUM);

        $this->mergerSubscriber->merge(new MergeEvent(self::API_TYPE, $this->smsMessage));

        $this->assertEquals(self::CONFIG_NUM, $this->smsMessage->getFrom());
        $this->assertEquals(self::API_TYPE, $this->smsMessage->getApiType());
    }

    public  function testMerge() {
        $num = '89211313412';

        $this->smsMessage->setFrom($num);
        $this->mergerSubscriber->merge(new MergeEvent(self::API_TYPE, $this->smsMessage));

        $this->assertEquals($num, $this->smsMessage->getFrom());
        $this->assertEquals(self::API_TYPE, $this->smsMessage->getApiType());
    }

    public function testGetSubscribedEvents() {
        $this->assertArrayHasKey(MergeEvents::ON_MERGE, $this->mergerSubscriber->getSubscribedEvents());
    }
} 