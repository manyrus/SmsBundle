<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 29.01.14
 * Time: 20:37
 */

namespace Manyrus\SmsBundle\Lib\EPochta;


use Manyrus\SmsBundle\Lib\Base\BaseConfig;

class Config extends BaseConfig{
    private $publicKey;
    private $privateKey;

    /**
     * @param string $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @param string $publicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }
} 