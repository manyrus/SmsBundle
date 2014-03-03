<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 20:58
 */

namespace Manyrus\SmsBundle\Tests\Lib;


use Manyrus\SmsBundle\Lib\ApiErrors;
use Manyrus\SmsBundle\Lib\SmsException;

class SmsExceptionTest extends \PHPUnit_Framework_TestCase{
    const ERROR = ApiErrors::AUTH_ERROR;
    const API_ERROR = '403';

    public function testException() {
        $exception = new SmsException(self::ERROR, self::API_ERROR);

        $this->assertEquals(self::ERROR, $exception->getError());
        $this->assertEquals(self::API_ERROR, $exception->getApiError());
    }
} 