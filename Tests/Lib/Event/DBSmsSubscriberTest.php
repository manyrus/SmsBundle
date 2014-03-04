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
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\EntityCreator;
use Manyrus\SmsBundle\Lib\Event\DBSmsSubscriber;
use Manyrus\SmsBundle\Lib\Event\SmsEvent;
use Manyrus\SmsBundle\Lib\SmsException;

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

    /**
     * @var EntityCreator
     */
    protected $entityCreator;

    public function setUp() {
        $this->manager = $this->getMock('\Doctrine\ORM\EntityManager', array('persist', 'flush'));
        $this->entityCreator = new EntityCreator(
            get_class($this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsError')),
            get_class($this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage'))
        );
        $this->subscriber = new DBSmsSubscriber($this->manager, $this->entityCreator);
        $this->smsMessage = $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage');
        $this->smsEvent = new SmsEvent($this->smsMessage);
    }

    public function testPersist() {

        $this->mockPersistAndFlush();
        $this->subscriber->flush($this->smsEvent);
    }

    public function testErrorSend() {
        $this->smsEvent->setException(new SmsException(ApiErrors::BAD_DATA));
        $this->mockPersistAndFlush();
        $this->subscriber->errorSend($this->smsEvent);
    }

    private function mockPersistAndFlush() {
        $self = $this;
        $msg = $this->smsMessage;
        $this->manager->expects($this->at(0))
            ->method('persist')
            ->with($this->isInstanceOf('Manyrus\SmsBundle\Entity\SmsMessage'))
            ->will($this->returnCallback(
                function(SmsMessage $sms) use($self, $msg){
                    $self->assertSame($sms, $msg);
                }
            ));

        $this->manager->expects($this->at(1))
            ->method('flush');
    }
} 