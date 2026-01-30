<?php

namespace BenTools\WebPushBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class WebPushExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('bentools_webpush.vapid_subject', $config['settings']['subject'] ?? $container->getParameter('router.request_context.host'));
        $container->setParameter('bentools_webpush.vapid_public_key', $config['settings']['public_key'] ?? null);
        $container->setParameter('bentools_webpush.vapid_private_key', $config['settings']['private_key'] ?? null);
        
        $loader = new PhpFileLoader($container, new FileLocator([__DIR__.'/../Resources/config/']));
        $loader->load('services.php');
    }

    public function getAlias(): string
    {
        return 'bentools_webpush';
    }
}
