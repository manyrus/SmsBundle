<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 28.01.14
 * Time: 20:35
 */

namespace Manyrus\SmsBundle\Lib\Decorators;


use Manyrus\SmsBundle\Lib\Base\ISmsRepository;
use Manyrus\SmsBundle\Lib\Base\SmsMerger;
use Manyrus\SmsBundle\Lib\Entity\SmsMessage;

class QueueSmsRepository implements ISmsRepository{
    /**
     * @var SmsMerger
     */
    private $smsMerger;

    /**
     * @var ISmsRepository
     */
    private $smsRepository;

    public function __construct(ISmsRepository $repository) {
        $this->smsRepository = $repository;
    }

    /**
     * @param \Manyrus\SmsBundle\Lib\Base\SmsMerger $smsMerger
     */
    public function setSmsMerger($smsMerger)
    {
        $this->smsMerger = $smsMerger;
    }



    public function send(SmsMessage $message) {
        $this->smsMerger->merge($message, $this);

        $message->setIsInQueue(true);

        return $message;
    }


    /**
     * @param SmsMessage $sms
     * @return mixed
     */
    public function checkStatus(SmsMessage $sms)
    {
        return $this->smsRepository->checkStatus($sms);
    }

    /**
     * @param SmsMessage $sms
     * @return mixed
     */
    public function getCost(SmsMessage $sms)
    {
        return $this->smsRepository->getCost($sms);
    }

    /**
     * @see Manyrus\SmsBundle\Lib\ApiType
     * @return mixed
     */
    public function getApiType()
    {
        $this->smsRepository->getApiType();
    }
}