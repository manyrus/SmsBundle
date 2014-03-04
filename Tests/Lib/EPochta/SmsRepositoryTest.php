<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 04.03.14
 * Time: 6:25
 */

namespace Manyrus\SmsBundle\Tests\Lib\EPochta;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\EPochta\BaseEPochtaRepository;
use Manyrus\SmsBundle\Lib\EPochta\Config;
use Manyrus\SmsBundle\Lib\EPochta\SmsRepository;

class SmsRepositoryTest extends \PHPUnit_Framework_TestCase{

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
        $this->smsMessage = $this->getMock('Manyrus\SmsBundle\Entity\SmsMessage');
    }

    public function testSend() {
        $this->config->setPrivateKey('manyrus');

        $this->buzz->expects($this->once())
            ->method('submit')
            ->will($this->returnCallback(function(){var_dump(func_num_args());}));

        $this->repo->send($this->smsMessage);
    }
} 