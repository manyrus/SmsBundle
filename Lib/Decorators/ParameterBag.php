<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 12.02.14
 * Time: 17:53
 */

namespace Manyrus\SmsBundle\Lib\Decorators;


class ParameterBag {
    /**
     * @var boolean
     */
    private $queueMode = false;
    /**
     * @var boolean
     */
    private $eventMode =false;

    /**
     * @param mixed $eventMode
     * @return $this
     */
    public function useEventMode($eventMode)
    {
        $this->eventMode = $eventMode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function isEventMode()
    {
        return $this->eventMode;
    }

    /**
     * @param mixed $queueMode
     * @return $this
     */
    public function useQueueMode($queueMode)
    {
        $this->queueMode = $queueMode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function isQueueMode()
    {
        return $this->queueMode;
    }


} 