<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 28.01.14
 * Time: 20:35
 */

namespace Manyrus\SmsBundle\Lib\Decorators;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\Base\ISmsRepository;
use Manyrus\SmsBundle\Lib\SmsException;
use Manyrus\SmsBundle\Lib\Status;

class QueueSmsRepository implements ISmsRepository{
    /**
     * @var ISmsRepository
     */
    private $smsRepository;

    public function __construct(ISmsRepository $repository) {
        $this->smsRepository = $repository;
    }



    public function send(SmsMessage $message) {
        if($message->getStatus() == Status::IN_QUEUE) {
            $this->smsRepository->send($message);
        } else {
            $message->setStatus(Status::IN_QUEUE);
        }

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
        return $this->smsRepository->getApiType();
    }
}