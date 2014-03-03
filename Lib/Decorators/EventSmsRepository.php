<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 02.02.14
 * Time: 21:38
 */

namespace Manyrus\SmsBundle\Lib\Decorators;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\Base\ISmsRepository;
use Manyrus\SmsBundle\Lib\Event\MergeEvent;
use Manyrus\SmsBundle\Lib\Event\MergeEvents;
use Manyrus\SmsBundle\Lib\Event\SmsEvent;
use Manyrus\SmsBundle\Lib\Event\SmsEvents;
use Manyrus\SmsBundle\Lib\SmsException;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventSmsRepository implements ISmsRepository{
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    private $smsRepository;

    public function __construct(ISmsRepository $repository) {
        $this->smsRepository = $repository;
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param SmsMessage $sms
     * @throws \Manyrus\SmsBundle\Lib\SmsException
     * @return mixed
     */
    public function send(SmsMessage $sms)
    {
        $event = new SmsEvent($sms);

        $this->eventDispatcher->dispatch(MergeEvents::ON_MERGE, new MergeEvent($this->getApiType(), $sms));

        $this->eventDispatcher->dispatch(SmsEvents::PRE_SEND, $event);//with merge
        try {
            $this->smsRepository->send($sms);
        } catch(SmsException $e) {
            $event->setException($e);
            $this->eventDispatcher->dispatch(SmsEvents::ERROR_SEND, $event);
            throw $e;
        }
        $this->eventDispatcher->dispatch(SmsEvents::POST_SEND, $event);//with merge
        return $sms;
    }

    /**
     * @param SmsMessage $sms
     * @return mixed
     */
    public function updateStatus(SmsMessage $sms)
    {
        $previousStatus = $sms->getStatus();
        $this->smsRepository->updateStatus($sms);
        if($previousStatus != $sms->getStatus()) {
            $this->eventDispatcher->dispatch(SmsEvents::SMS_CHANGED, new SmsEvent($sms));
        }
        return $sms;
    }

    /**
     * @param SmsMessage $sms
     * @return mixed
     */
    public function updateCost(SmsMessage $sms)
    {
        $this->eventDispatcher->dispatch(MergeEvents::ON_MERGE, new MergeEvent($this->getApiType(), $sms));
        $previousCost = $sms->getCost();
        $this->smsRepository->updateCost($sms);
        if($previousCost != $sms->getCost()) {
            $this->eventDispatcher->dispatch(SmsEvents::SMS_CHANGED, new SmsEvent($sms));
        }
        return $sms;
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