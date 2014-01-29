<?php

namespace Manyrus\SmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ManyrusSmsBundle:Default:index.html.twig', array('name' => $name));
    }
}
