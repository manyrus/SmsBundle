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
use Manyrus\SmsBundle\Lib\SmsException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendCommand extends ContainerAwareCommand{


    protected function configure()
    {
        $this
            ->setName('sms:send')
            ->setDescription('Send in queue sms messages')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityRepository $repo */
        $repo = $this->getContainer()->get("doctrine.orm.entity_manager")->getRepository($this->getContainer()->getParameter("manyrus.sms_bundle.sms_entity"));

        /** @var SmsMessage[] $messages */
        $messages = $repo->findBy(array('isInQueue'=>true));

        foreach($messages as $sms) {
            $smsRepo = $this->getSmsRepository($sms->getApiType());
            try{
                $smsRepo->send($sms);
                $output->writeln("<info>Message #{$sms->getMessageId()} was send</info>");
            } catch(SmsException $e) {
                $output->writeln("<error>Message #{$sms->getMessageId()} was not send({$e->getError()})</error>");
            }
        }
    }

    /**
     * @param $type
     * @return \Manyrus\SmsBundle\Lib\Base\ISmsRepository
     * @throws \RuntimeException
     */
    private function getSmsRepository($type) {
        if($type == ApiType::EPOCHTA_API) {
            return $this->getContainer()->get('manyrus.sms_bundle.decorator_factory')->
                createDecorators(
                    $this->getContainer()->get("manyrus.sms_bundle.epochta.sms_repository"), false
                );
        } else if($type == ApiType::SMS_RU_API) {
            //return $this->getContainer()->get('manyrus.sms_bundle.epochta.sms_repository');
        } else {
            throw new \RuntimeException('Unknown SmsRepository type');
        }
    }
} 