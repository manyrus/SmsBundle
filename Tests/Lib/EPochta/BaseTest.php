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
use Manyrus\SmsBundle\Lib\EPochta\Config;
use Manyrus\SmsBundle\Lib\EPochta\SmsRepository;

abstract class BaseTest extends \PHPUnit_Framework_TestCase{
    /**
     * @var \Buzz\Browser
     */
    protected $buzz;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var SmsRepository
     */
    protected $repo;

    /**
     * @var SmsMessage
     */
    protected $smsMessage;

    protected function setUp() {
        $this->config = new Config();
        $this->buzz = $this->getMock('\Buzz\Browser');
        $this->repo = new SmsRepository();
        $this->repo->setConfig($this->config);
        $this->repo->setBuzz($this->buzz);
        $this->smsMessage = $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage');
        $this->config->setPrivateKey('manyrus');

    }
    protected function createBuzzSubmitWithReturn($return, $expect) {
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

    protected function getArgsSendArray() {
        return array(
            'http://atompark.com/api/sms/3.0/sendSMS',
            array(
                'sender'=>'79216778055',
                'text' => 'hello!',
                'phone' => '1111',
                'version' => '3.0',
                'action' => 'sendSMS',
                'key' => '',
                'test' => 0,
                'sum' => '21281aa165ce4f984eb0a4fc33ac5b47'
            ), RequestInterface::METHOD_GET, array()
        );
    }
} 