<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 21:22
 */

namespace Manyrus\SmsBundle\Tests\Entity;


use Manyrus\SmsBundle\Entity\SmsError;
use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\ApiType;

class SmsErrorTest extends \PHPUnit_Framework_TestCase{
    const MESSAGE = 'helllllo!!!!';
    const ERROR_TYPE = ApiErrors::AUTH_ERROR;

    /**
     * @var SmsError
     */
    protected $smsError;

    /**
     * @var SmsMessage
     */
    protected $smsMessage;
    protected function setUp() {
        $this->smsError = $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsError');
        $this->smsMessage = $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage');

    }

    public function testConstructor() {
        $error = $this->getMockBuilder('Manyrus\SmsBundle\Entity\SmsError')
            ->setConstructorArgs(array(self::ERROR_TYPE, $this->smsMessage))
            ->getMockForAbstractClass();
        $this->assertEquals(self::ERROR_TYPE, $error->getErrorType());
        $this->assertEquals($this->smsMessage, $error->getMessage());
    }

    public function testSmsError() {
        $this->smsError->setMessage($this->smsMessage);
        $this->smsError->setErrorType(self::ERROR_TYPE);

        $this->assertEquals(self::ERROR_TYPE, $this->smsError->getErrorType());
        $this->assertEquals($this->smsMessage, $this->smsError->getMessage());
    }
} 