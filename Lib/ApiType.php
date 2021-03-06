<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 27.01.14
 * Time: 23:29
 */

namespace Manyrus\SmsBundle\Lib;


abstract class ApiType {
    const EPOCHTA_API = 'epochta';
    const SMS_RU_API = 'sms_ru';

    const ALL = 'all';

    public static function getApiTypes() {
        return array( self::EPOCHTA_API, self::SMS_RU_API);
    }
}