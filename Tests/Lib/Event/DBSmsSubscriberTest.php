<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 19:49
 */

namespace Manyrus\SmsBundle\Tests\Lib\Event;


use Doctrine\ORM\EntityManager;
use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\Event\DBSmsSubscriber;
use Manyrus\SmsBundle\Lib\Event\SmsEvent;

class DBSmsSubscriberTest extends \PHPUnit_Framework_TestCase{

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var DBSmsSubscriber
     */
    protected $subscriber;

    /**
     * @var SmsMessage
     */
    protected  $smsMessage;

    /**
     * @var SmsEvent
     */
    protected $smsEvent;

    public function setUp() {
        $this->manager = $this->getMock('\Doctrine\ORM\EntityManager', array('persist', 'flush'));
        $this->subscriber = new DBSmsSubscriber($this->manager);
        $this->smsMessage = $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage');
        $this->smsEvent = new SmsEvent($this->smsMessage);
    }

    public function testPersist() {
        $self = $this;
        $msg = $this->smsMessage;
        $this->manager->expects($this->at(0))
            ->method('persist')
            ->with($this->isInstanceOf('Manyrus\SmsBundle\Entity\SmsMessage'))
            ->will($this->returnCallback(
                function(SmsMessage $sms) use($self, $msg){
                    $self->assertEquals($sms, $msg);
                }
            ));

        $this->manager->expects($this->at(1))
            ->method('flush');

        $this->subscriber->flush($this->smsEvent);
    }
} 