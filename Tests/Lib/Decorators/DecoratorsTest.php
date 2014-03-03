<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 0:04
 */

namespace Manyrus\SmsBundle\Tests\Lib\Decorators;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\Base\ISmsRepository;
use Manyrus\SmsBundle\Tests\MockGenerators\SmsRepositoryMock;

abstract class DecoratorsTest  extends \PHPUnit_Framework_TestCase{
    /**
     * @var SmsMessage
     */
    public $smsMessage;

    /**
     * @var ISmsRepository
     */
    public $smsRepository;


    /**
     * @var SmsRepositoryMock
     */
    public $smsRepositoryGen;

    protected function setUp() {
        $this->smsMessage = $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage');
        $this->smsRepository = $this->getMockForAbstractClass('Manyrus\SmsBundle\Lib\Base\ISmsRepository');
        $this->smsRepositoryGen = new SmsRepositoryMock($this);
    }

    public function testApiType() {
        $this->smsRepositoryGen->apiTypeMethod($this->once());
        $class = $this->getClassName();
        /** @var ISmsRepository $test */
        $test = new $class($this->smsRepository);

        $this->assertEquals($test->getApiType(), SmsRepositoryMock::API_TYPE);
    }

    abstract protected function getClassName();
} 