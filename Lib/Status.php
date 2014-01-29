<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 27.01.14
 * Time: 23:35
 */

namespace Manyrus\SmsBundle\Lib;


abstract class Status {
    const IN_PROCESS = 'in_process';
    const ERROR = 'error';
    const DELIVERED = 'delivered';
    const SENT = 'sent';
    const SPAM = 'spam';
    const UNDELIVERED = 'undelivered';
}