<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 08.03.14
 * Time: 10:45
 */

namespace Manyrus\SmsBundle\Tests\Lib\EPochta\SmsRepository;


use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\EPochta\SmsRepository;

class GetApiTypeTest extends \PHPUnit_Framework_TestCase{
    public function testGetApiType() {
        $repo = new SmsRepository();
        $this->assertEquals(ApiType::EPOCHTA_API, $repo->getApiType());
    }
} 