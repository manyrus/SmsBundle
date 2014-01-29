<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 29.01.14
 * Time: 20:38
 */

namespace Manyrus\SmsBundle\Lib;


/**
 * Class SmsException
 * @package MaxiBooking\Bundle\SmsBundle\Lib\Exception
 */
class SmsException extends \Exception{
    private $apiError;

    private $error;

    /**
     * @param string $error
     * @param string $apiError
     */
    function __construct($error, $apiError)
    {
        $this->apiError = $apiError;
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getApiError()
    {
        return $this->apiError;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }


}