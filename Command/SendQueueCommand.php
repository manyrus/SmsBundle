<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 11.02.14
 * Time: 21:42
 */

namespace Manyrus\SmsBundle\Command;


use Doctrine\ORM\EntityRepository;
use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\ApiType;
use Manyrus\SmsBundle\Lib\Decorators\ParameterBag;
use Manyrus\SmsBundle\Lib\SmsException;
use Manyrus\SmsBundle\Lib\Status;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendQueueCommand extends ContainerAwareCommand{


    protected function configure()
    {
        $this
            ->setName('sms:send:queue')
            ->setDescription('Send in queue sms messages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityRepository $repo */
        $repo = $this->getContainer()->get("doctrine.orm.entity_manager")->getRepository($this->getContainer()->getParameter("manyrus.sms_bundle.sms_entity"));

        /** @var SmsMessage[] $messages */
        $messages = $repo->findBy(array('status'=>Status::IN_QUEUE));

        foreach($messages as $sms) {
            $smsRepo = $this->getContainer()->get('manyrus.sms_bundle.sms_repository_factory')->getRepository($sms->getApiType());
            try{
                $smsRepo->send($sms);
                $output->writeln("<info>Message #{$sms->getMessageId()} was send</info>");
            } catch(SmsException $e) {
                $output->writeln("<error>Message #{$sms->getMessageId()} was not send({$e->getError()})</error>");
            }
        }
    }
} 