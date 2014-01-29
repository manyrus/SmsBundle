<?php

namespace Manyrus\SmsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ManyrusSmsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->loadSmsGate($config, $loader);

        $this->loadConfig($config, $container, 'manyrus_sms_bundle');
    }

    private function loadConfig($config, ContainerBuilder $container, $root) {

        foreach($config as $key=>$value) {
            if(is_array($value)) {
                $this->loadConfig($config[$key], $container, $root.'.'.$key);
            } else {
                $container->setParameter($root.'.'.$key, $value);
            }

        }
    }

    private function loadSmsGate($config,\Symfony\Component\Config\Loader\Loader $loader) {

        $loader->load('event.xml');
        $loader->load('base.xml');
        $loader->load('checker.xml');
        $loader->load('epochta.xml');
        $loader->load('sms_ru.xml');
        if($config['api_class'] == 'EPochta') {
            $loader->load('alias/epochta.xml');
        } else if($config['api_class'] == 'sms_ru') {
            $loader->load('alias/sms_ru.xml');
        }
    }
}
