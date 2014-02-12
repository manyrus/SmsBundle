<?php

namespace Manyrus\SmsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ManyrusSmsExtension extends Extension implements PrependExtensionInterface
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

        $this->loadConfig($config, $container, 'manyrus.sms_bundle');
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

    public function prepend(ContainerBuilder $builder) {
        $bundles = $builder->getParameter("kernel.bundles");
        $configs = $builder->getExtensionConfig( $this->getAlias() );
        if(isset($bundles['DoctrineBundle'])) {
            $config = array(
                'orm'=>array(
                    'resolve_target_entities'=>array(
                        'Manyrus\SmsBundle\Entity\SmsMessage' => $configs[0]['sms_entity'],
                        'Manyrus\SmsBundle\Entity\SmsError' => $configs[0]['error_entity']
                    )
                )
            );
            $builder->prependExtensionConfig('doctrine', $config);
        }
    }

    private function loadSmsGate($config,\Symfony\Component\Config\Loader\Loader $loader) {

        $loader->load('base.xml');
        $loader->load('decorator_factory.xml');
        $loader->load('repository_factory.xml');
        $providers = array('epochta', 'sms_ru');
        $to_load=array(
            //'checker.xml',
            'config.xml',
            'decorator.xml',
            'repositories.xml'
        );

        foreach($providers as $provider) {

            foreach($to_load as $file) {
                $loader->load($provider.'/'.$file);
            }

            if($provider === $config['api_class']) {
                $loader->load($provider.'/alias.xml');
            }
        }

        $loader->load('event.xml');
    }
}
