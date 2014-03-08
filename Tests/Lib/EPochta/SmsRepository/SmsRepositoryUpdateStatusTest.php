<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 08.03.14
 * Time: 9:13
 */

namespace Manyrus\SmsBundle\Tests\Lib\EPochta\SmsRepository;


use Buzz\Message\RequestInterface;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\EntityCreator;
use Manyrus\SmsBundle\Lib\EPochta\BaseEPochtaRepository;
use Manyrus\SmsBundle\Lib\EPochta\SmsRepository;
use Manyrus\SmsBundle\Lib\SmsException;
use Manyrus\SmsBundle\Lib\Status;

class SmsRepositoryUpdateStatusTest extends BaseSmsRepositoryRepositoryTest{

    /**
     * @var EntityCreator
     */
    protected $entityCreator;

    protected function setUp() {
        parent::setUp();
        $this->entityCreator = new EntityCreator(get_class($this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsError')), get_class($this->getMockForAbstractClass('Manyrus\SmsBundle\Entity\SmsMessage')));
        $this->smsMessage->setMessageId("12123");
        $this->repo->setEntityCreator($this->entityCreator);
    }

    public function testInProcessStatus() {
        $this->assertStatus('0', Status::IN_PROCESS);
    }

    public function testSentStatus() {
        $this->assertStatus('SENT', Status::SENT);
    }

    public function testDeliveredStatus() {
        $this->assertStatus('DELIVERED', Status::DELIVERED);
    }

    public function testUnDeliveredStatus() {
        $this->assertStatus('UNDELIVERED', Status::UNDELIVERED);
    }
    public function testSpamStatus() {
        $this->assertStatus('SPAM', Status::SPAM);
    }
    public function testInvalidPhone() {
        $this->assertStatus('INVALID_PHONE_NUMBER', Status::ERROR);

        $this->assertNotNull($this->smsMessage->getError());
        $error = $this->smsMessage->getError();
        $this->assertEquals(ApiErrors::BAD_ADDRESSER, $error->getErrorType());
    }

    public function testBadIdException() {
        $this->createBuzzSubmitWithReturn($this->returnValue(
            '{"error":"error_invalid_id", "result":false, "code":55}'
        ), $this->getSubmitExpectedArgs());

        try {
            $this->repo->updateStatus($this->smsMessage);
            $this->fail('SmsException was not thrown');
        } catch(SmsException $e) {
            $this->assertEquals(ApiErrors::BAD_ID, $e->getError());
        } catch(\Exception $e) {
            $this->fail('Unknown exception');
        }
    }

    public function testUnknownException() {
        $this->createBuzzSubmitWithReturn($this->returnValue(
            '{"error":"error_invalid", "result":false, "code":55}'
        ), $this->getSubmitExpectedArgs());

        try {
            $this->repo->updateStatus($this->smsMessage);
            $this->fail('SmsException was not thrown');
        } catch(SmsException $e) {
            $this->assertEquals(ApiErrors::UNKNOWN_ERROR, $e->getError());
        } catch(\Exception $e) {
            $this->fail('Unknown exception');
        }
    }

    protected function assertStatus($returnApi, $expects) {
        $this->createBuzzSubmitWithReturn($this->returnValue(
            sprintf('{"result":{"phone":["%s"],"sentdate":["0000-00-00 00:00:00"],"donedate":["0000-00-00 00:00:00"],"status":["%s"]}}', $this->smsMessage->getTo(), $returnApi)
        ), $this->getSubmitExpectedArgs());

        $this->repo->updateStatus($this->smsMessage);

        $this->assertEquals($expects, $this->smsMessage->getStatus());
    }

    protected function callTestMethod()
    {
        $this->repo->updateStatus($this->smsMessage);
    }

    protected function getSubmitExpectedArgs()
    {
        return array(sprintf("http://atompark.com/api/sms/%s/%s", BaseEPochtaRepository::VERSION, SmsRepository::GET_STATUS), array(
            'id' => $this->smsMessage->getMessageId(),
            'version' => BaseEPochtaRepository::VERSION,
            'action' => SmsRepository::GET_STATUS,
            'key' => '',
            'test' => (int) $this->config->getIsTest(),
            'sum' => '1e0b793120de5f67f93d85538ef3a5e5'
        ), RequestInterface::METHOD_GET, array());
    }
}