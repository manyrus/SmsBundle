<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 06.03.14
 * Time: 21:18
 */

namespace Manyrus\SmsBundle\Tests\Lib\EPochta\SmsRepository;


use Buzz\Message\RequestInterface;
use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\EPochta\SmsRepository;
use Manyrus\SmsBundle\Tests\Lib\EPochta\BaseRepositoryTest;

abstract class BaseSmsRepositoryRepositoryTest extends BaseRepositoryTest{
    /**
     * @var SmsMessage
     */
    protected $smsMessage;

    /**
     * @var SmsRepository
     */
    protected $repo;

    protected  function setUp() {
        parent::setUp();

        $this->repo = new SmsRepository();
        $this->repo->setConfig($this->config);
        $this->repo->setBuzz($this->buzz);
        $this->smsMessage = $this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage');

        $this->smsMessage->setFrom('79216778055');
        $this->smsMessage->setMessage('hello!');
        $this->smsMessage->setTo('1111');
    }


} 