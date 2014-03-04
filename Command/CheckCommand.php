<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 12.02.14
 * Time: 22:10
 */

namespace Manyrus\SmsBundle\Command;


use Doctrine\ORM\EntityRepository;
use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\SmsException;
use Manyrus\SmsBundle\Lib\Status;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends ContainerAwareCommand{
    protected function configure()
    {
        $this
            ->setName('sms:check')
            ->setDescription('Check sms status')
            ->addOption(
                'id',
                null,
                InputOption::VALUE_REQUIRED,
                'Message id'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityRepository $repo */
        $repo = $this->getContainer()->get("doctrine.orm.entity_manager")->getRepository($this->getContainer()->getParameter("manyrus.sms_bundle.entities.sms"));


        /** @var SmsMessage[] $messages */
        if($input->getOption('id') != null) {
            $messages = $repo->findBy(array('id'=>$input->getOption('id')));
        } else {
            $messages = $repo->findBy(array('status'=>Status::IN_PROCESS));
        }



        foreach($messages as $sms) {
            $smsRepo = $this->getContainer()->get('manyrus.sms_bundle.sms_repository_factory')->getRepository($sms->getApiType());
            try{
                if($sms->getStatus() == Status::DELIVERED) {
                    $output->writeln("<info>Message #{$sms->getId()} is delivered</info>");
                    break;
                }
                $smsRepo->updateStatus($sms);
                if($sms->getStatus() !== Status::IN_PROCESS) {
                    $output->writeln("<info>Message #{$sms->getId()} changed status, now it {$sms->getStatus()}</info>");
                } else {
                    $output->writeln("<comment>Message #{$sms->getId()} is in process</comment>");
                }

            } catch(SmsException $e) {
                $output->writeln("<error>Message #{$sms->getId()} has errors({$e->getError()})</error>");
            }
        }
    }
} 