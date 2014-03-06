<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 06.03.14
 * Time: 21:23
 */

namespace Manyrus\SmsBundle\Entity;


class Client {
    protected $balance;

    /**
     * @param mixed $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }




} 