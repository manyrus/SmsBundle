<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 12.02.14
 * Time: 21:41
 */

namespace Manyrus\SmsBundle\Lib\SmsRu;


use Buzz\Message\RequestInterface;
use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\Base\BaseRepository;
use Manyrus\SmsBundle\Lib\SmsException;

class BaseSmsRuRepository extends BaseRepository{
    const API_URL = 'http://sms.ru/';

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
        $params['api_id'] = $this->config->getKey();
        $params['test'] = (int) $this->config->getIsTest();
        return explode("\n",$this->buzz->submit(self::API_URL.$action, $params, RequestInterface::METHOD_GET)->getContent());
    }

    protected function createException($response) {
        switch($response[0]) {
            case '200':
                $exception = new SmsException(ApiErrors::AUTH_ERROR, $response[0]);
                break;

            case '300':
                $exception = new SmsException(ApiErrors::AUTH_ERROR, $response[0]);
                break;
            case '301':
                $exception = new SmsException(ApiErrors::AUTH_ERROR, $response[0]);
                break;

            case '302':
                $exception = new SmsException(ApiErrors::AUTH_ERROR, $response[0]);
                break;

            default:
                $exception = new SmsException(ApiErrors::UNKNOWN_ERROR, $response[0]);
                break;
        }
        return $exception;
    }
} 