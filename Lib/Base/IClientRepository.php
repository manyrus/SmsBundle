<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 27.01.14
 * Time: 23:32
 */

namespace Manyrus\SmsBundle\Lib\Base;


use Manyrus\SmsBundle\Entity\Client;

interface IClientRepository {
    /**
     * Get current balance
     * @param \Manyrus\SmsBundle\Entity\Client $c
     * @return \Manyrus\SmsBundle\Entity\Client
     */
    public function updateBalance(Client $c);
} 