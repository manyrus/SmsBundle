<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 29.01.14
 * Time: 20:40
 */

namespace Manyrus\SmsBundle\Lib;


abstract class ApiErrors {
    const AUTH_ERROR = 'auth_error';
    const UNKNOWN_ERROR = 'unknown_error';
    const LOW_BALANCE = 'low_balance';
    const BAD_RECEIVER = 'bad_receiver';
    const EMPTY_MESSAGE = 'empty_message';
    const LONG_MESSAGE = 'long_message';
    const LIMIT_ERROR = 'limit_error';

    const BAD_DATA = 'bad_data';

    const BAD_ADDRESSER = 'bad_addresser';

    const MESSAGE_NOT_FOUND = 'message_not_found';

    const BAD_ID = 'bad_id';
} 