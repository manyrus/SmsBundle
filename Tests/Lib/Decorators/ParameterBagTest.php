<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 03.03.14
 * Time: 13:15
 */

namespace Manyrus\SmsBundle\Tests\Lib\Decorators;


use Manyrus\SmsBundle\Lib\Decorators\ParameterBag;

class ParameterBagTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var ParameterBag
     */
    protected $bag;

    protected function setUp() {
        $this->bag = new ParameterBag();
    }

    public function testEventMode()
    {
        $this->assertFalse($this->bag->isEventMode());
        $this->assertEquals($this->bag, $this->bag->useEventMode(true));
        $this->assertTrue($this->bag->isEventMode());
    }

    public function testQueueMode()
    {
        $this->assertFalse($this->bag->isQueueMode());
        $this->assertEquals($this->bag, $this->bag->useQueueMode(true));
        $this->assertTrue($this->bag->isQueueMode());
    }
}
 