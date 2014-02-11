<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 02.02.14
 * Time: 21:57
 */

namespace Manyrus\SmsBundle\Lib\Event;


use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DBSmsSubscriber implements EventSubscriberInterface{


    /**
     * @var EntityManager
     */
    private $manager;


    /**
     * @param \Doctrine\ORM\EntityManager $manager
     */
    public function __construct(EntityManager $manager) {
        $this->manager = $manager;
    }

    /**
     * @param SmsEvent $event
     */
    public function afterSend(SmsEvent $event) {
        $this->manager->persist($event->getMessage());
        $this->manager->flush();
    }

    /**
     * @param SmsEvent $event
     */
    public function onError(SmsEvent $event) {
        $this->manager->persist($event->getMessage());
        $this->manager->flush();
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            SmsEvents::POST_SEND => 'afterSend',
            SmsEvents::ERROR_SEND => 'onError'
        );
    }
}