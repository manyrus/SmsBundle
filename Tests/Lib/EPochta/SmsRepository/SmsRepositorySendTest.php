<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 04.03.14
 * Time: 12:53
 */

namespace Manyrus\SmsBundle\Tests\Lib\EPochta\SmsRepository;


use Buzz\Message\RequestInterface;
use Exception;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\SmsException;
use Manyrus\SmsBundle\Lib\Status;
use Manyrus\SmsBundle\Tests\Lib\EPochta\BaseRepositoryTest;

class SmsRepositoryTest extends BaseSmsRepositoryRepositoryTest{

    public function testSend() {
        $this->createBuzzSubmitWithReturn($this->returnValue(
            '{"result":{"id":"22-21"}}'
        ), $this->getSubmitExpectedArgs());

        $this->repo->send($this->smsMessage);

        $this->assertEquals("22-21", $this->smsMessage->getMessageId());
        $this->assertEquals(Status::IN_PROCESS, $this->smsMessage->getStatus());
    }

    public function testBadSmsMessage() {
        $this->smsMessage->setFrom(null);
        $this->setExpectedException('\RuntimeException');
        $this->repo->send($this->smsMessage);

    }

    public function testExceptionLowBalance() {
        $this->sendDataException(304, ApiErrors::LOW_BALANCE);
    }

    public function testExceptionLowBalance2() {
        $this->sendDataException(305, ApiErrors::LOW_BALANCE);
    }

    public function testExceptionLowBalance3() {
        $this->sendDataException(103, ApiErrors::LOW_BALANCE);
    }

    public function testUnknownException() {
        $this->sendDataException(-122, ApiErrors::UNKNOWN_ERROR);
    }

    /**
     * @param $code
     * @param $apiError
     * @internal param $data
     * @return \Exception|SmsException
     */
    private  function sendDataException($code, $apiError) {

        $this->createBuzzSubmitWithReturn(
            $this->returnValue('{"error":"error", "code":"'.$code.'", "result":"false"}'),
            $this->getSubmitExpectedArgs()
        );

        try {
            $this->repo->send($this->smsMessage);

            $this->fail('There was no exception');
        } catch(SmsException $e) {
            $this->assertEquals($apiError, $e->getError());
            $this->assertEquals($code, $e->getApiError());
        } catch(Exception $e) {
            $this->fail('unknown exception');
        }
    }

    protected function getSubmitExpectedArgs() {
        return array(
            'http://atompark.com/api/sms/3.0/sendSMS',
            array(
                'sender'=>$this->smsMessage->getFrom(),
                'text' => $this->smsMessage->getMessage(),
                'phone' => $this->smsMessage->getTo(),
                'version' => '3.0',
                'action' => 'sendSMS',
                'key' => '',
                'test' => 0,
                'sum' => '21281aa165ce4f984eb0a4fc33ac5b47'
            ), RequestInterface::METHOD_GET, array()
        );
    }

    protected function callTestMethod() {
        $this->repo->send($this->smsMessage);
    }
} 