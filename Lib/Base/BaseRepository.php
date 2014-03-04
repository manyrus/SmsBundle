<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 28.01.14
 * Time: 20:35
 */

namespace Manyrus\SmsBundle\Lib\Base;


use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class BaseRepository {
    /**
     * @var \Buzz\Browser
     */
    protected $buzz;


    /**
     * @param mixed $buzz
     */
    public function setBuzz($buzz)
    {
        $this->buzz = $buzz;
    }
} 