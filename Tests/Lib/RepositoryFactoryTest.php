<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 21:00
 */

namespace Manyrus\SmsBundle\Tests\Lib;


use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\RepositoryFactory;
use Symfony\Component\DependencyInjection\Container;

class RepositoryFactoryTest extends \PHPUnit_Framework_TestCase{
    const ONE = 'one';
    const TWO = 'two';

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * @var RepositoryFactory
     */
    protected $factory;


    protected function setUp() {
        $this->container = $this->getMock('\Symfony\Component\DependencyInjection\Container');

        $this->factory = new RepositoryFactory();
        $this->factory->setContainer($this->container);
    }

    public function testFactory() {
        $this->container->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValueMap(array(
                array('manyrus.sms_bundle.decorated.epochta.sms_repository',Container::EXCEPTION_ON_INVALID_REFERENCE, self::ONE),
                array('manyrus.sms_bundle.decorated.sms_ru.sms_repository',Container::EXCEPTION_ON_INVALID_REFERENCE, self::TWO)
            )));


        $this->assertEquals(self::ONE, $this->factory->getRepository(ApiType::EPOCHTA_API));
        $this->assertEquals(self::TWO, $this->factory->getRepository(ApiType::SMS_RU_API));

        $this->setExpectedException('\RuntimeException');
        $this->factory->getRepository('http://rvb.ru/pushkin/01text/01versus/0423_36/1828/0480.htm');
    }
} 