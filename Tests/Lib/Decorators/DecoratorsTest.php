<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 0:04
 */

namespace Manyrus\SmsBundle\Tests\Lib\Decorators;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\Base\ISmsRepository;
use Manyrus\SmsBundle\Lib\Status;

abstract class DecoratorsTest  extends \PHPUnit_Framework_TestCase{
    /**
     * @return SmsMessage
     */
    protected  function getSmsMessage() {
        return $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage');
    }

    public function testApiType() {
        $mock = $this->getMockForAbstractClass('Manyrus\SmsBundle\Lib\Base\ISmsRepository');;

        $mock->expects($this->once())
            ->method('getApiType')
            ->will($this->returnValue(ApiType::EPOCHTA_API)
            );

        $class = $this->getClassName();
        /** @var ISmsRepository $test */
        $test = new $class($mock);

        $this->assertEquals($test->getApiType(), ApiType::EPOCHTA_API);
    }

    abstract protected function getClassName();
} 