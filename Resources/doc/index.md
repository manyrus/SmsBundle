Getting Started With ManyrusSmsBundle
==================================
This bundle will help you with a sms delivering.
In the 21 century it is a great feedback for clients.
## Installation
Installation include next steps:
1. Download ManyrusSmsBundle via composer
2. Enable the Bundle
3. Create your sms, error class
4. Configure bundle
5. Update your database schema
### 1. Download ManyrusSmsBundle via composer
Add into your composer.json the next lines:
```js
{
    "require": {
        "manyrus/sms-bundle": "dev-master"
    }
}
```
Then tell composer to download the bundle:
``` bash
$ php composer.phar update manyrus/sms-bundle
```
### 2. Enable the bundle
Enable the bundle in the kernel:
``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Manyrus\SmsBundle\ManyrusSmsBundle(),
    );
}
```
### 3. Create your sms, error class
The feature of this bundle is that all sms, error objects are persisting by doctrine.
You must create sms, error class and extends it from `Entity\SmsMessage` and `Entity\SmsError`
#### Example, with annotations
`SmsMessage`:
``` php
namespace Manyrus\TestBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
/**
 * Class Message
 * @package Manyrus\TestBundle\Entity
 * @ORM\Table(name="sms")
 * @ORM\Entity()
 */
class SmsMessage extends \Manyrus\SmsBundle\Entity\SmsMessage{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct($to = null) {
        $this->to = $to;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

}
```
`Error`:
```php
<?php
namespace Manyrus\TestBundle\Entity;


use Manyrus\SmsBundle\Entity\SmsError;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class Message
 * @package Manyrus\TestBundle\Entity
 * @ORM\Table(name="sms_error")
 * @ORM\Entity()
 */
class Error extends SmsError{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected  $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

}

```

### 4. Configure bundle
Just add in config.eml the next lines:
``` yaml
manyrus_sms:
  from: '79216778055'#default from message
  api_type: sms_ru#api_type, epochta|sms_ru
  min_balance: 100 #event will be created, if the balance lower this value
  is_test_mode: false #set, if the api test mode
  is_queue_mode: false #put by default sms message in queue or not

  entities: #your entities
    error: Manyrus\TestBundle\Entity\Error
    sms: Manyrus\TestBundle\Entity\SmsMessage

  epochta:#config for web services
    auth:
      public_key: "111"
      private_key: "111"

  sms_ru:
    auth:
      key: "111"
```

###5. Update your database schema
For ORM run the following command.
``` bash
$ php app/console doctrine:schema:update --force
```

Congratulations! You installed the bundle!