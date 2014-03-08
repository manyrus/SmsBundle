<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 27.01.14
 * Time: 23:31
 */

namespace Manyrus\SmsBundle\Lib\Base;


abstract class BaseConfig {
    private $from;
    private $isTest = false;

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $isTest
     */
    public function setIsTest($isTest)
    {
        $this->isTest = $isTest;
    }

    /**
     * @return mixed
     */
    public function getIsTest()
    {
        return $this->isTest;
    }
} 