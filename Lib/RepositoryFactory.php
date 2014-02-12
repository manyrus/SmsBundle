<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 12.02.14
 * Time: 22:04
 */

namespace Manyrus\SmsBundle\Lib;


use Symfony\Component\DependencyInjection\Container;

class RepositoryFactory {
    /**
     * @var Container
     */
    private $container;

    /**
     * @param mixed $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }



    public function getRepository($type) {
        if($type == ApiType::EPOCHTA_API) {
            $smsRepository = $this->container->get('manyrus.sms_bundle.decorated.epochta.sms_repository');
        } else if($type == ApiType::SMS_RU_API) {
            $smsRepository = $this->container->get('manyrus.sms_bundle.decorated.sms_ru.sms_repository');
        } else {
            throw new \RuntimeException('Unknown SmsRepository type');
        }

        return $smsRepository;
    }
} 