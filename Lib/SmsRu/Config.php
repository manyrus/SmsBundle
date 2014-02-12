<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 12.02.14
 * Time: 21:44
 */

namespace Manyrus\SmsBundle\Lib\SmsRu;


use Manyrus\SmsBundle\Lib\Base\BaseConfig;

class Config extends BaseConfig{
    private $key;

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }


} 