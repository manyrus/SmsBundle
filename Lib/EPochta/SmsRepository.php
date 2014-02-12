<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 29.01.14
 * Time: 20:49
 */

namespace Manyrus\SmsBundle\Lib\EPochta;


use Manyrus\SmsBundle\Entity\ErrorManager;
use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\Base\ISmsRepository;
use Manyrus\SmsBundle\Lib\SmsException;
use Manyrus\SmsBundle\Lib\Status;


class SmsRepository extends BaseEPochtaRepository implements ISmsRepository{

    const SEND_SMS = 'sendSMS';
    const GET_PRICE = 'checkCampaignPriceGroup';
    const GET_STATUS = 'getCampaignDeliveryStats';
    const GET_COST = 'checkCampaignPriceGroup';



    /**
     * @var ErrorManager
     */
    private $errorManager;


    /**
     * @param SmsMessage $sms
     * @throws \Manyrus\SmsBundle\Lib\SmsException
     * @return mixed
     */
    public function send(SmsMessage $sms)
    {
        $request = array();
        $request['sender'] = $sms->getFrom();
        $request['text'] = $sms->getMessage();
        $request['phone'] = $sms->getTo();

        $result = $this->sendRequest($request, self::SEND_SMS);

        if(!empty($result['error'])) {
            switch($result['code']) {
                case 304:
                    $exception= new SmsException(ApiErrors::LOW_BALANCE, $result['code']);
                    break;
                case -1:
                    $exception= new SmsException(ApiErrors::AUTH_ERROR, $result['code']);
                    break;
                case 305:
                    $exception = new SmsException(ApiErrors::LOW_BALANCE, $result['code']);
                    break;
                case 103:
                    $exception = new SmsException(ApiErrors::LOW_BALANCE, $result['code']);
                    break;
                default:
                    $exception = $this->generateException($result['code']);
                    break;
            }

            throw $exception;
        }

        $sms->setMessageId($result['result']['id']);
        $sms->setStatus(Status::IN_PROCESS);
        return $sms;
    }

    /**
     * @param SmsMessage $sms
     * @throws \Manyrus\SmsBundle\Lib\SmsException
     * @return mixed
     */
    public function checkStatus(SmsMessage $sms)
    {
        $request = array();
        $request['id'] = $sms->getMessageId();

        $result = $this->sendRequest($request, self::GET_STATUS);

        if(!empty($result['error'])){
            if($result['error'] == 'error_invalid_id') {
                $exception = new SmsException(ApiErrors::BAD_ID, $result['code']);
            } else {
                $exception = $this->generateException($result['code']);
            }

            $sms->setStatus(Status::ERROR);
            $sms->setError($this->errorManager->generateClass(ApiErrors::BAD_ID));

            throw $exception;
        }

        $status = $result['result']['status'][0];
        if($status == '0') {
            $sms->setStatus(Status::IN_PROCESS);
        } else if($status == 'SENT') {
            $sms->setStatus(Status::SENT);
        } else if($status == 'DELIVERED') {
            $sms->setStatus(Status::DELIVERED);
        } else if($status == 'UNDELIVERED') {
            $sms->setStatus(Status::UNDELIVERED);
        } elseif($status == 'SPAM') {
            $sms->setStatus(Status::SPAM);
        } elseif($status == 'INVALID_PHONE_NUMBER') {
            $sms->setStatus(Status::ERROR);
            $sms->setError($this->errorManager->generateClass(ApiErrors::BAD_ADDRESSER));
        }

        return $sms;
    }

    /**
     * @param SmsMessage $message
     * @throws \Manyrus\SmsBundle\Lib\SmsException
     * @return mixed
     */
    public function getCost(SmsMessage $message)
    {

        $request = array();
        $request['sender'] = $message->getFrom();
        $request['text'] = $message->getMessage();
        $request['phones'] = json_encode(array(array($message->getTo())));

        $result = $this->sendRequest($request, self::GET_PRICE);

        if(!empty($result['error'])) {
            throw $this->generateException($result['code']);
        }

        $message->setCost($result['result']['price']);

        return $message;
    }

    /**
     * @see Manyrus\SmsBundle\Lib\ApiType
     * @return mixed
     */
    public function getApiType()
    {
        return ApiType::EPOCHTA_API;
    }
}