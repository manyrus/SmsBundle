<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 28.02.14
 * Time: 20:09
 */

namespace Manyrus\SmsBundle\Lib;


use Manyrus\SmsBundle\Entity\SmsError;
use Manyrus\SmsBundle\Entity\SmsMessage;
use \RuntimeException;

class EntityCreator {
    private $smsClass;

    private $errorClass;

    function __construct($errorClass, $smsClass)
    {
        if(!is_subclass_of($errorClass, 'Manyrus\SmsBundle\Entity\SmsError') || !is_subclass_of($smsClass, 'Manyrus\SmsBundle\Entity\SmsMessage')) {
            throw new RuntimeException('Bad parameters');
        }
        $this->errorClass = $errorClass;
        $this->smsClass = $smsClass;
    }

    /**
     * @return SmsMessage
     */
    public function createSms() {
        return new $this->smsClass();
    }

    /**
     * @param $errorType
     * @param $message
     * @return SmsError
     */
    public function createError($errorType=null, $message=null) {
        return new $this->errorClass($errorType, $message);
    }
} 