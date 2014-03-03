<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 13:58
 */

namespace Manyrus\SmsBundle\Tests\Lib\Decorators;


use Manyrus\SmsBundle\Lib\Decorators\DecoratorsFactory;
use Manyrus\SmsBundle\Lib\Decorators\ParameterBag;

class DecoratorsFactoryTest extends \PHPUnit_Framework_TestCase {
    protected $container;

    protected $smsRepository;

    /**
     * @var ParameterBag
     */
    protected $parameterBag;

    /**
     * @var DecoratorsFactory
     */
    protected $factory;

    protected $eventDispatcher;

    protected function setUp() {
        $this->factory = new DecoratorsFactory();
        $this->parameterBag = new ParameterBag();
        $this->container = $this->getMock('\Symfony\Component\DependencyInjection\Container');
        $this->smsRepository = $this->getMockForAbstractClass('Manyrus\SmsBundle\Lib\Base\ISmsRepository');
        $this->eventDispatcher = $this->getMock('\Symfony\Component\EventDispatcher\EventDispatcher');

        $this->factory->setContainer($this->container);
    }

    public function testEventCreateDecorators() {
        $this->parameterBag->useEventMode(true);

        $this->generateContainer($this->once());

        $eventRepository = $this->factory->createDecorators($this->smsRepository, $this->parameterBag);
        $this->assertInstanceOf('Manyrus\SmsBundle\Lib\Decorators\EventSmsRepository', $eventRepository);

        $this->parameterBag->useEventMode(false);
        $eventRepository = $this->factory->createDecorators($this->smsRepository, $this->parameterBag);
        $this->assertNotInstanceOf('Manyrus\SmsBundle\Lib\Decorators\EventSmsRepository', $eventRepository);
    }

    public function testQueueCreateDecorators() {
        $this->parameterBag->useQueueMode(true);

        $this->generateContainer($this->once());

        $eventRepository = $this->factory->createDecorators($this->smsRepository, $this->parameterBag);
        $this->assertInstanceOf('Manyrus\SmsBundle\Lib\Decorators\QueueSmsRepository', $eventRepository);

        $this->parameterBag->useEventMode(true);//event&&queue
        $eventRepository = $this->factory->createDecorators($this->smsRepository, $this->parameterBag);
        $this->assertInstanceOf('Manyrus\SmsBundle\Lib\Decorators\EventSmsRepository', $eventRepository);
    }

    private function generateContainer($expects) {
        $this->container->expects($expects)
            ->method('get')
            ->with($this->equalTo('event_dispatcher'))
            ->will($this->returnValue($this->eventDispatcher));
    }
}
 