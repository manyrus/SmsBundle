<?php
/**
 * Created by PhpStorm.
 * User: manyrus
 * Date: 06.03.14
 * Time: 21:06
 */

namespace Manyrus\SmsBundle\Lib\EPochta;


use Manyrus\SmsBundle\Entity\Client;
use Manyrus\SmsBundle\Lib\Base\IClientRepository;

class ClientRepository extends BaseEPochtaRepository implements IClientRepository{
    const GET_BALANCE = 'getUserBalance';

    /**
     * Get current balance
     * @param \Manyrus\SmsBundle\Entity\Client $client
     * @return \Manyrus\SmsBundle\Entity\Client
     */
    public function updateBalance(Client $client)
    {
        $request = array('currency'=>'RUB');//TODO:currency

        $result = $this->sendRequest($request, self::GET_BALANCE);

        $client->setBalance($result['result']['balance_currency']);

        return $client;
    }
}