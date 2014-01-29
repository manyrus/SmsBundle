<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 29.01.14
 * Time: 20:27
 */

namespace Manyrus\SmsBundle\Lib\Base;


use Manyrus\SmsBundle\Lib\Entity\SmsMessage;

class SmsMerger {
    /**
     * @var BaseConfig
     */
    private $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    public function merge(SmsMessage $message, ISmsRepository $repo) {
        if($message->getFrom() == null) {
            $message->setFrom($this->config->getFrom());
        }
        $message->setApiType($repo->getApiType());
    }
} 