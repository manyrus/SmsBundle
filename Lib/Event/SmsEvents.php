<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 02.02.14
 * Time: 18:02
 */

namespace Manyrus\SmsBundle\Lib\Event;


/**
 * Class SmsEvents
 * @package MaxiBooking\Bundle\SmsBundle\Lib
 */
final class SmsEvents {
    /**
     * This event occurs after sms message has been sent
     */
    const POST_SEND = 'sms.postSend';

    const PRE_SEND = 'sms.preSend';

    const ERROR_SEND = 'sms.errorSend';

    const STATUS_CHANGED = 'sms.statusChanged';
} 