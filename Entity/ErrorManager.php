<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 29.01.14
 * Time: 21:50
 */

namespace Manyrus\SmsBundle\Entity;


class ErrorManager {
    private $errorClass;

    function __construct($errorClass)
    {
        $this->errorClass = $errorClass;
    }

    public function generateClass($errorType, $message = null) {
        return new $this->errorClass($errorType, $message);
    }
} 