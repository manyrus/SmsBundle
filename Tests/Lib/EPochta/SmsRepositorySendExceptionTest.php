<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 04.03.14
 * Time: 6:25
 */

namespace Manyrus\SmsBundle\Tests\Lib\EPochta;


use Buzz\Message\RequestInterface;
use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\EPochta\Config;
use Manyrus\SmsBundle\Lib\EPochta\SmsRepository;
use Manyrus\SmsBundle\Lib\SmsException;
use Symfony\Component\Config\Definition\Exception\Exception;

class SmsRepositorySendExceptionTest extends BaseTest{

    public function setUp() {
        parent::setUp();


        $this->smsMessage->setFrom('79216778055');
        $this->smsMessage->setMessage('hello!');
        $this->smsMessage->setTo('1111');
    }


    public function testBadDataException() {
        $exc = $this->sendDataException('{"hello":true}');

        $this->assertEquals(ApiErrors::BAD_DATA, $exc->getError());
    }

    public function testBadDataException2() {
        $exc = $this->sendDataException('{"hello"');
        $this->assertEquals(ApiErrors::BAD_DATA, $exc->getError());
    }

    public function testBadSmsMessage() {
        $this->smsMessage->setFrom(null);
        $this->setExpectedException('\RuntimeException');
        $this->repo->send($this->smsMessage);

    }

    public function testExceptionLowBalance() {
        $this->assertSendException(304, ApiErrors::LOW_BALANCE);
    }

    public function testExceptionLowBalance2() {
        $this->assertSendException(305, ApiErrors::LOW_BALANCE);
    }

    public function testExceptionLowBalance3() {
        $this->assertSendException(103, ApiErrors::LOW_BALANCE);
    }

    public function testExceptionAuth() {
        $this->assertSendException(-1, ApiErrors::AUTH_ERROR);
    }

    public function testUnknownException() {
        $this->assertSendException(-122, ApiErrors::UNKNOWN_ERROR);
    }

    /**
     * @param $data
     * @return \Exception|SmsException
     */
    private  function sendDataException($data) {

        $this->createBuzzSubmitWithReturn(
            $this->returnValue($data),
            $this->getArgsSendArray()
        );

        try {
            $this->repo->send($this->smsMessage);
        } catch(SmsException $e) {
            return $e;
        } catch(Exception $e) {
            $this->fail('unknown exception');
        }
        $this->fail('There was no exception');

    }

    /**
     * @param $code
     * @param $apiError
     */
    private function assertSendException($code, $apiError) {
        $e = $this->sendDataException('{"error":"error", "code":"'.$code.'", "result":"false"}');

        $this->assertEquals($apiError, $e->getError());
        $this->assertEquals($code, $e->getApiError());
    }





} 