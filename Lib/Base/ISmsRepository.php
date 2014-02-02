<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 27.01.14
 * Time: 23:33
 */

namespace Manyrus\SmsBundle\Lib\Base;



use Manyrus\SmsBundle\Lib\Entity\SmsMessage;

interface ISmsRepository {
    /**
     * @param SmsMessage $sms
     * @return SmsMessage
     */
    public function send(SmsMessage $sms);

    /**
     * @param SmsMessage $sms
     * @return SmsMessage
     */
    public function checkStatus(SmsMessage $sms);

    /**
     * @param SmsMessage $sms
     * @return SmsMessage
     */
    public function getCost(SmsMessage $sms);

    /**
     * @see Manyrus\SmsBundle\Lib\ApiType
     * @return string
     */
    public function getApiType();
} 