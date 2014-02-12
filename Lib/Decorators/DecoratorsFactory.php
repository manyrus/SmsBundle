<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 11.02.14
 * Time: 20:26
 */

namespace Manyrus\SmsBundle\Lib\Decorators;


use Manyrus\SmsBundle\Lib\Base\ISmsRepository;
use Symfony\Component\DependencyInjection\Container;

class DecoratorsFactory {
    /**
     * @var Container
     */
    private $container;

     /**
     * @param \Symfony\Component\DependencyInjection\Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }



    public function createDecorators(ISmsRepository $smsRepository, ParameterBag $parameters) {

        if($parameters->isQueueMode()) {
            $smsRepository = new QueueSmsRepository($smsRepository);//TODO: we must first init this. I don't like it, must think about
        }

        if($parameters->isEventMode()) {
            $smsRepository = new EventSmsRepository($smsRepository);
            $smsRepository->setEventDispatcher($this->container->get("event_dispatcher"));
        }

        return $smsRepository;
    }


} 