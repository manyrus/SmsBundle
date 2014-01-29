<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 28.01.14
 * Time: 20:34
 */

namespace Manyrus\SmsBundle\Lib\EPochta;


use Buzz\Message\RequestInterface;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\Base\BaseRepository;
use Manyrus\SmsBundle\Lib\SmsException;

abstract class BaseEPochtaRepository extends BaseRepository{
    const API_URL = 'http://atompark.com/api/sms/3.0/';
    const VERSION = '3.0';

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @param $params
     * @param $action
     * @return mixed
     */
    protected function sendRequest($params, $action) {
        $params['version'] = self::VERSION;
        $params['action'] = $action;
        $params['key'] = $this->config->getPublicKey();
        $params['test'] = (int) $this->config->getIsTest();
        $params['sum'] = $this->generateSum($params);

        return json_decode($this->buzz->submit(self::API_URL.$action, $params, RequestInterface::METHOD_GET)->getContent(), true);

    }

    /**
     * @param $params
     * @return string
     */
    private function generateSum($params) {
        ksort($params);

        $sum='';
        foreach ($params as $k=>$v) {
            $sum.=$v;
        }
        return md5($sum.$this->config->getPrivateKey());
    }

    /**
     * @param $status
     * @return SmsException
     */
    protected function generateException($status) {
        if($status == -1) {
            return new SmsException(ApiErrors::AUTH_ERROR, $status);
        } else {
            return new SmsException(ApiErrors::UNKNOWN_ERROR, $status);
        }
    }
} 