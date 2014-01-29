<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 29.01.14
 * Time: 20:49
 */

namespace Manyrus\SmsBundle\Lib\EPochta;


use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\Base\ISmsRepository;
use Manyrus\SmsBundle\Lib\Base\SmsMerger;
use Manyrus\SmsBundle\Lib\Entity\SmsMessage;


class SmsRepository extends BaseEPochtaRepository implements ISmsRepository{

    const SEND_SMS = 'sendSMS';
    const GET_PRICE = 'checkCampaignPriceGroup';
    const GET_STATUS = 'getCampaignDeliveryStats';
    const GET_COST = 'checkCampaignPriceGroup';


    /**
     * @var SmsMerger
     */
    private $smsMerger;

    /**
     * @param \Manyrus\SmsBundle\Lib\Base\SmsMerger $smsMerger
     */
    public function setSmsMerger($smsMerger)
    {
        $this->smsMerger = $smsMerger;
    }


    /**
     * @param SmsMessage $sms
     * @return mixed
     */
    public function send(SmsMessage $sms)
    {
        $this->smsMerger->merge($sms, $this);
    }

    /**
     * @param SmsMessage $sms
     * @return mixed
     */
    public function checkStatus(SmsMessage $sms)
    {
        // TODO: Implement checkStatus() method.
    }

    /**
     * @param SmsMessage $message
     * @throws \Manyrus\SmsBundle\Lib\SmsException
     * @return mixed
     */
    public function getCost(SmsMessage $message)
    {
        $this->smsMerger->merge($message, $this);

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