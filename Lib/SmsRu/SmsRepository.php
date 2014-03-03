<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 12.02.14
 * Time: 21:45
 */

namespace Manyrus\SmsBundle\Lib\SmsRu;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\Base\ISmsRepository;
use Manyrus\SmsBundle\Lib\SmsException;
use Manyrus\SmsBundle\Lib\Status;

class SmsRepository extends BaseSmsRuRepository implements ISmsRepository{
    const SEND = 'sms/send';
    const COST = 'sms/cost';
    const STATUS = 'sms/status';


    /**
     * @param SmsMessage $sms
     * @return SmsMessage
     * @throws SmsException
     */
    public function send(SmsMessage $sms)
    {
        $request['to'] = $sms->getTo();
        $request['from'] = $sms->getFrom();
        $request['text'] = $sms->getMessage();

        $response = $this->sendRequest($request, self::SEND);

        if($response[0] != '100') {
            switch($response[0]) {
                case '201':
                    $exception = new SmsException(ApiErrors::LOW_BALANCE, $response[0]);
                    break;

                case '202':
                    $exception = new SmsException(ApiErrors::LOW_BALANCE, $response[0]);
                    break;

                case '203':
                    $exception = new SmsException(ApiErrors::EMPTY_MESSAGE, $response[0]);
                    break;

                case '204':
                    $exception = new SmsException(ApiErrors::BAD_ADDRESSER, $response[0]);
                    break;

                case '205':
                    $exception = new SmsException(ApiErrors::LONG_MESSAGE, $response[0]);
                    break;

                case '206':
                    $exception = new SmsException(ApiErrors::BAD_ADDRESSER, $response[0]);
                    break;
                default:
                    $exception = $this->createException($response);
            }
            throw $exception;
        }

        $sms->setMessageId($response[1]);
        $sms->setStatus(Status::IN_PROCESS);

        return $sms;
    }

    /**
     * @param SmsMessage $sms
     * @throws \Manyrus\SmsBundle\Lib\SmsException
     * @return SmsMessage
     */
    public function updateStatus(SmsMessage $sms)
    {
        if($sms->getMessageId() == '000-00000') {//для тестовых смс
            $sms->setStatus(Status::DELIVERED);
            return $sms;
        }

        $request['id'] = $sms->getMessageId();
        $response = $this->sendRequest($request, self::STATUS);

        if($response[0] > 103) {
            var_dump($response);
            switch($response[0]) {
                case '-1':
                    $exception = new SmsException(ApiErrors::MESSAGE_NOT_FOUND, $response[0]);
                    break;

                default:
                    $exception = $this->createException($response);
            }

            throw $exception;
        }
        $this->changeStatus($response[0], $sms);

        return $sms;
    }

    /**
     * @param SmsMessage $sms
     * @throws \Manyrus\SmsBundle\Lib\SmsException
     * @return SmsMessage
     */
    public function updateCost(SmsMessage $sms)
    {
        $request['to'] = $sms->getTo();
        $request['text'] = $sms->getMessage();

        $response = $this->sendRequest($request, self::COST);

        if($response[0] != '100') {
            switch($response[0]) {
                case '202':
                    $exception = new SmsException(ApiErrors::BAD_ADDRESSER, $response[0]);
                    break;

                case '207':
                    $exception = new SmsException(ApiErrors::BAD_ADDRESSER, $response[0]);
                    break;

                default:
                    $exception = $this->createException($response);
            }
            throw $exception;
        }

        return $response[1];
    }

    /**
     * @see Manyrus\SmsBundle\Lib\ApiType
     * @return string
     */
    public function getApiType()
    {
        return ApiType::SMS_RU_API;
    }

    private function changeStatus($status,SmsMessage $sms) {
        switch($status) {
            case '103'://будем оповещать только при точном получении сообшения
                $sms->setStatus(Status::DELIVERED);
        }
    }
}