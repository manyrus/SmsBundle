<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 27.01.14
 * Time: 23:32
 */

namespace Manyrus\SmsBundle\Lib\Base;


interface IClientRepository {
    /**
     * Get current balance
     * @return float
     */
    public function getBalance();
} 