<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 02.02.14
 * Time: 21:57
 */

namespace Manyrus\SmsBundle\Lib\Event;


use Doctrine\ORM\EntityManager;
use Manyrus\SmsBundle\Lib\EntityCreator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DBSmsSubscriber implements EventSubscriberInterface{


    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var EntityCreator
     */
    private $creator;
    /**
     * @param \Doctrine\ORM\EntityManager $manager
     * @param \Manyrus\SmsBundle\Lib\EntityCreator $creator
     */
    public function __construct(EntityManager $manager, EntityCreator $creator) {
        $this->manager = $manager;
        $this->creator = $creator;
    }

    /**
     * @param SmsEvent $event
     */
    public function flush(SmsEvent $event) {
        $this->manager->persist($event->getMessage());
        $this->manager->flush();
    }

    public function errorSend(SmsEvent $event) {
        $exception = $event->getException();

        $error = $this->creator->createError($exception->getApiError(), $event->getMessage());
        $event->getMessage()->setError($error);

        $this->flush($event);
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
            SmsEvents::POST_SEND => 'flush',
            SmsEvents::ERROR_SEND => 'errorSend',
            SmsEvents::SMS_CHANGED=>'flush'
        );
    }
}