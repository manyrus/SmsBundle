<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 02.02.14
 * Time: 22:37
 */

namespace Manyrus\SmsBundle\Lib\Event;


use Manyrus\SmsBundle\Lib\Base\BaseConfig;
use Manyrus\SmsBundle\Lib\Base\SmsMerger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\EventListener\MergeCollectionListener;

class MergerSubscriber implements EventSubscriberInterface {
    /**
     * @var BaseConfig
     */
    private $config;


    function __construct(BaseConfig $config)
    {
        $this->config = $config;
    }

    public function merge(MergeEvent $event) {
        $message = $event->getMessage();
        if($message->getFrom() == null) {
            $message->setFrom($this->config->getFrom());
        }

        $message->setApiType($event->getApiType());
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
            MergeEvents::ON_MERGE => 'merge'
        );
    }
}