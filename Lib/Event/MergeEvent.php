<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.02.14
 * Time: 0:20
 */

namespace Manyrus\SmsBundle\Lib\Event;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Symfony\Component\EventDispatcher\Event;

class MergeEvent extends Event{
    /**
     * @var SmsMessage
     */
    private $message;

    /**
     * @var string
     */
    private $apiType;

    function __construct($apiType, $message)
    {
        $this->apiType = $apiType;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getApiType()
    {
        return $this->apiType;
    }

    /**
     * @return \Manyrus\SmsBundle\Entity\SmsMessage
     */
    public function getMessage()
    {
        return $this->message;
    }


} 