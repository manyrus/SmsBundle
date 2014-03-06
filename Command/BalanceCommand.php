<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 06.03.14
 * Time: 22:27
 */

namespace Manyrus\SmsBundle\Command;


use Manyrus\SmsBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BalanceCommand extends ContainerAwareCommand{
    protected function configure()
    {
        $this
            ->setName('sms:balance')
            ->setDescription('Check sms balance')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $client = new Client();

        $clientRepository = $this->getContainer()->get('manyrus.sms_bundle.client_repository');

        $clientRepository->updateBalance($client);

        $output->writeln("Your current balance is:{$client->getBalance()} RUB");
    }
} 