<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 06.03.14
 * Time: 21:06
 */

namespace Manyrus\SmsBundle\Tests\Lib\EPochta\ClientRepository;



use Manyrus\SmsBundle\Entity\Client;
use Manyrus\SmsBundle\Lib\EPochta\ClientRepository;
use Manyrus\SmsBundle\Tests\Lib\EPochta\BaseRepositoryTest;

abstract class BaseClientRepositoryRepositoryTest extends BaseRepositoryTest{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;


    protected function setUp() {
        parent::setUp();
        $this->client = new Client();
        $this->clientRepository = new ClientRepository();

        $this->clientRepository->setBuzz($this->buzz);
        $this->clientRepository->setConfig($this->config);
    }

} 