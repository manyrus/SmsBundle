<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 14.02.14
 * Time: 5:17
 */

namespace Manyrus\SmsBundle\Command;


use Manyrus\SmsBundle\Entity\SmsMessage;
use Manyrus\SmsBundle\Lib\Decorators\ParameterBag;
use Manyrus\SmsBundle\Lib\SmsException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendCommand extends ContainerAwareCommand{
    protected function configure()
    {
        $this
            ->setName('sms:send')
            ->setDescription('Send sms messages')

            ->addOption(
                'to',
                null,
                InputOption::VALUE_REQUIRED,
                'Whom this message will be send'
            )
            ->addOption(
                'message',
                null,
                InputOption::VALUE_REQUIRED,
                'Sms body'
            )
            ->addOption(
                'from',
                null,
                InputOption::VALUE_REQUIRED,
                'Set, from whom this message will be send'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var DialogHelper $dialog */
        $dialog = $this->getHelperSet()->get('dialog');

        $sms = $this->getContainer()->get('manyrus.sms_bundle.entity_creator')->createSms();


        if($input->getOption('from') == null) {
            $default = $this->getContainer()->getParameter('manyrus.sms_bundle.from');
            $sms->setFrom($dialog->ask($output, "<info>From [{$default}]</info>: ", $default));
        } else {
            $sms->setFrom($input->getOption('from'));
        }

        if($input->getOption('to') == null) {
            $sms->setTo($dialog->ask($output, '<info>To</info>: '));
        } else {
            $sms->setTo($input->getOption('to'));
        }

        if($input->getOption('message') == null) {
            $sms->setMessage($dialog->ask($output, '<info>Message body</info>: '));
        } else {
            $sms->setMessage($input->getOption('message'));
        }

        $smsRepository = $this->getContainer()->get('manyrus.sms_bundle.decorator_factory')->createDecorators(
            $this->getContainer()->get('manyrus.sms_bundle.current.sms_repository'),
            (new ParameterBag())->useEventMode(true)
        );

        try{
            $smsRepository->send($sms);
            $output->writeln("<comment>Message was send! Check his status with command \"sms:check --id={$sms->getId()}\"</comment>");
        } catch(SmsException $e) {
            $output->writeln("<error>Something go wrong, {$e->getError()}</error>");
        }
    }
} 