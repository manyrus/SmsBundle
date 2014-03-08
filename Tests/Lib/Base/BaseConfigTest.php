<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 18:17
 */

namespace Manyrus\SmsBundle\Tests\Lib\Base;


use Manyrus\SmsBundle\Lib\Base\BaseConfig;

class BaseConfigTest extends \PHPUnit_Framework_TestCase{
    /**
     * @var BaseConfig
     */
    private $config;

    public function setUp() {
        $this->config = $this->getMockForAbstractClass('Manyrus\SmsBundle\Lib\Base\BaseConfig');
    }

    public function testFrom() {
        $this->assertNull($this->config->getFrom());
        $from = '79216778055';
        $this->config->setFrom($from);
        $this->assertEquals($from, $this->config->getFrom());
    }

    public function testIsTest() {
        $this->assertFalse($this->config->getIsTest());
        $this->config->setIsTest(true);
        $this->assertTrue($this->config->getIsTest());
    }
} 