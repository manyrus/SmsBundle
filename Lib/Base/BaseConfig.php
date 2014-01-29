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

    private $max;

    private $isTest;

    /**
     * @var boolean
     */
    protected $isQueueMode;

    /**
     * @param mixed $isQueueMode
     */
    public function setIsQueueMode($isQueueMode)
    {
        $this->isQueueMode = $isQueueMode;
    }



    /**
     * @return mixed
     */
    public function isQueueMode()
    {
        return $this->isQueueMode;
    }

    /**
     * @param mixed $max
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     * @return mixed
     */
    public function getMax()
    {
        return $this->max;
    }

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