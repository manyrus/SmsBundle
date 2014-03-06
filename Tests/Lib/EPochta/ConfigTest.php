<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 06.03.14
 * Time: 22:32
 */

namespace Manyrus\SmsBundle\Tests\Lib\EPochta;


use Manyrus\SmsBundle\Lib\EPochta\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase{
    /**
     * @var Config
     */
    protected $config;

    public function setUp() {
        $this->config = new Config();
    }

    public function testPrivateKey() {
        $this->assertNull($this->config->getPrivateKey());
        $this->config->setPrivateKey('hellllo!');
        $this->assertEquals('hellllo!', $this->config->getPrivateKey());
    }

    public function testPublicKey() {
        $this->assertNull($this->config->getPublicKey());
        $this->config->setPublicKey('heloo!!');
        $this->assertEquals('heloo!!', $this->config->getPublicKey());
    }
} 