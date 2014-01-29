<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 28.01.14
 * Time: 0:00
 */

namespace Manyrus\SmsBundle\Lib\Entity;


abstract class SmsError {
    private $errorType;

    private $message;

    function __construct($errorType, $message = null)
    {
        $this->errorType = $errorType;
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getErrorType()
    {
        return $this->errorType;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }


} 