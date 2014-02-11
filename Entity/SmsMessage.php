<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 27.01.14
 * Time: 23:34
 */

namespace Manyrus\SmsBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Manyrus\SmsBundle\Lib\Status;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Notification
 * @package MaxiBooking\Bundle\NotificationBundle\Entity
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @Annotation
 */
abstract class SmsMessage{
    const ERROR = Status::ERROR;

    /**
     * @ORM\Column(name="status", type="string")
     * @Assert\NotBlank()
     */
    protected $status = Status::IN_PROCESS;


    /**
     * @var double
     *
     * @ORM\Column(name="cost", type="float", nullable=true)
     */
    private $cost = 0;

    /**
     * @var double
     *
     * @ORM\Column(name="api_message_id", type="string")
     */
    private $messageId = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Manyrus\SmsBundle\Entity\SmsError", cascade={"persist"})
     * @ORM\JoinColumn(name="error_id", nullable=true, referencedColumnName="id")
     */
    private $error;

    /**
     * @ORM\Column(name="api_type", type="string")
     */
    private $apiType;

    /**
     * @ORM\Column(name="from_message", type="string")
     * @Assert\NotBlank()
     */
    protected  $from;

    /**
     * @ORM\Column(name="to_message", type="string")
     * @Assert\NotBlank()
     */
    protected  $to;

    /**
     * @ORM\Column(name="message", type="string")
     * @Assert\NotBlank()
     */
    protected  $message;


    /**
     * @ORM\Column(name="is_in_queue", type="boolean")
     * @Assert\NotBlank()
     */
    protected $isInQueue = false;


    /**
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @param mixed $isInQueue
     */
    public function setIsInQueue($isInQueue)
    {
        $this->isInQueue = $isInQueue;
    }

    /**
     * @return mixed
     */
    public function getIsInQueue()
    {
        return $this->isInQueue;
    }



    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Запускается перед созданием объекта
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->created = new \DateTime();
    }
    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $apiType
     */
    public function setApiType($apiType)
    {
        $this->apiType = $apiType;
    }

    /**
     * @return mixed
     */
    public function getApiType()
    {
        return $this->apiType;
    }



    /**
     * @param mixed $messageId
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->messageId;
    }
    /**
     * @param mixed $error
     */


    public function setError($error)
    {
        $this->status = Status::ERROR;
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }


    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        if($this->error != null) return self::ERROR;
        return $this->status;
    }

}