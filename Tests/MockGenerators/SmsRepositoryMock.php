<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 17:03
 */

namespace Manyrus\SmsBundle\Tests\MockGenerators;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\SmsException;
use Manyrus\SmsBundle\Lib\Status;
use Manyrus\SmsBundle\Tests\Lib\Decorators\DecoratorsTest;

class SmsRepositoryMock {
    const MESSAGE_ID = '0-0';
    const SEND_STATUS = Status::IN_PROCESS;
    const API_ERROR = ApiErrors::LOW_BALANCE;
    const API_TYPE = ApiType::EPOCHTA_API;
    const COST = 5;
    const UPDATE_STATUS = Status::DELIVERED;

    /**
     *
     * @var DecoratorsTest
     */
    private $test;

    function __construct($test)
    {
        $this->test = $test;
    }

    public function sendMethod($expects, $exception = false) {
        $with = $this->test->smsRepository->expects($expects)
            ->method('send')
            ->with($this->test->isInstanceOf('Manyrus\SmsBundle\Entity\SmsMessage'));

        if(!$exception) {
            $with->will($this->test->returnCallback(
                function(SmsMessage $m) {
                    $m->setStatus(SmsRepositoryMock::SEND_STATUS);
                    $m->setMessageId(SmsRepositoryMock::MESSAGE_ID);
                }
            ));
        } else {
            $with->will($this->test->returnCallback(
                function(SmsMessage $m) {
                    throw new SmsException(SmsRepositoryMock::API_ERROR, SmsRepositoryMock::API_ERROR);
                }
            ));
        }
    }

    public  function updateStatus($excepts) {
        $this->test->smsRepository->expects($excepts)
            ->method('updateStatus')
            ->with($this->test->isInstanceOf('Manyrus\SmsBundle\Entity\SmsMessage'))
            ->will($this->test->returnCallback(
                function(SmsMessage $m) {
                    $m->setStatus(SmsRepositoryMock::UPDATE_STATUS);
                }
            ));
    }

    public function updateCost($excepts) {
        $this->test->smsRepository->expects($excepts)
            ->method('updateCost')
            ->with($this->test->isInstanceOf('Manyrus\SmsBundle\Entity\SmsMessage'))
            ->will($this->test->returnCallback(
                function(SmsMessage $m) {
                    $m->setCost(SmsRepositoryMock::COST);
                }
            ));
    }

    public function apiTypeMethod($excepts) {
        $this->test->smsRepository->expects($excepts)
            ->method('getApiType')
            ->will($this->test->returnValue(SmsRepositoryMock::API_TYPE));
    }
} 