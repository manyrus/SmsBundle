<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 04.03.14
 * Time: 12:29
 */

namespace Manyrus\SmsBundle\Tests\Lib\EPochta;


use Buzz\Message\RequestInterface;
use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\EPochta\Config;
use Manyrus\SmsBundle\Lib\EPochta\SmsRepository;
use Manyrus\SmsBundle\Lib\SmsException;

abstract class BaseRepositoryTest extends \PHPUnit_Framework_TestCase{
    /**
     * @var \Buzz\Browser
     */
    protected $buzz;

    /**
     * @var Config
     */
    protected $config;



    protected function setUp() {
        $this->config = new Config();
        $this->buzz = $this->getMock('\Buzz\Browser');
        $this->config->setPrivateKey('manyrus');

    }
    protected function createBuzzSubmitWithReturn(\PHPUnit_Framework_MockObject_Stub_Return $return, $expect) {
        $self = $this;
        $this->buzz->expects($this->once())
            ->method('submit')

            ->will($this->returnCallback(function() use($return, $self, $expect){

                $self->assertEquals($expect, func_get_args());
                $mock = $self->getMockForAbstractClass('Buzz\Message\MessageInterface');
                $mock->expects($self->once())
                    ->method('getContent')
                    ->will($return);
                return $mock;
            }));
    }

    public function testAuthException() {
        $this->createBuzzSubmitWithReturn($this->returnValue('{"error":"Wrong public key.","code":"-1","result":"false"}'), $this->getSubmitExpectedArgs());
        $this->setExpectedException('Manyrus\SmsBundle\Lib\SmsException');

        $this->callTestMethod();

        /** @var  $e SmsException*/
        $e = $this->getExpectedException();

        $this->assertSame(ApiErrors::AUTH_ERROR, $e->getError());
    }

    public function testBadDataException() {
        $this->createBuzzSubmitWithReturn($this->returnValue('{"error":"Wrong public key.","code":'), $this->getSubmitExpectedArgs());
        $this->setExpectedException('Manyrus\SmsBundle\Lib\SmsException');

        $this->callTestMethod();

        /** @var  $e SmsException*/
        $e = $this->getExpectedException();

        $this->assertSame(ApiErrors::BAD_DATA, $e->getError());
    }

    public function testBadDataException2() {
        $this->createBuzzSubmitWithReturn($this->returnValue('{"heelo":"true"}'), $this->getSubmitExpectedArgs());
        $this->setExpectedException('Manyrus\SmsBundle\Lib\SmsException');

        $this->callTestMethod();

        /** @var  $e SmsException*/
        $e = $this->getExpectedException();

        $this->assertSame(ApiErrors::BAD_DATA, $e->getError());
    }

    abstract protected  function callTestMethod();
    abstract protected function getSubmitExpectedArgs();
} 