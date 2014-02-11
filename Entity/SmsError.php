<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 28.01.14
 * Time: 0:00
 */

namespace Manyrus\SmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class SmsError
 * @package Manyrus\SmsBundle\Entity
 * @ORM\MappedSuperclass
 * @Annotation
 */
abstract class SmsError {
    /**
     * @ORM\Column(name="error_type", type="string")
     * @Assert\NotBlank()
     */
    private $errorType;

    /**
     * @ORM\ManyToOne(targetEntity="Manyrus\SmsBundle\Entity\SmsMessage", cascade={"persist"})
     * @ORM\JoinColumn(name="message_id", nullable=true, referencedColumnName="id")
     * @var SmsMessage
     */
    private $message;

    function __construct($errorType = null, $message = null)
    {
        $this->errorType = $errorType;
        $this->message = $message;
    }

    /**
     * @param mixed $errorType
     */
    public function setErrorType($errorType)
    {
        $this->errorType = $errorType;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }



    /**
     * @return mixed
     */
    public function getErrorType()
    {
        return $this->errorType;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }


} 