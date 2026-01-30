<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use BenTools\WebPushBundle\Action\RegisterSubscriptionAction;
use BenTools\WebPushBundle\Command\WebPushGenerateKeysCommand;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionManagerInterface;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionManagerRegistry;
use BenTools\WebPushBundle\Sender\PushMessageSender;
use BenTools\WebPushBundle\Twig\WebPushTwigExtension;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set(WebPushGenerateKeysCommand::class)
        ->tag('console.command');

    $services->set(WebPushTwigExtension::class)
        ->arg('$publicKey', param('bentools_webpush.vapid_public_key'))
        ->tag('twig.extension');

    $services->set(UserSubscriptionManagerRegistry::class);
    
    $services->alias(UserSubscriptionManagerInterface::class, UserSubscriptionManagerRegistry::class);

    $services->set(RegisterSubscriptionAction::class)
        ->public()
        ->arg('$registry', service(UserSubscriptionManagerRegistry::class))
        ->tag('controller.service_arguments');

    $services->set(PushMessageSender::class)
        ->arg('$auth', [
            'VAPID' => [
                'subject' => param('bentools_webpush.vapid_subject'),
                'publicKey' => param('bentools_webpush.vapid_public_key'),
                'privateKey' => param('bentools_webpush.vapid_private_key'),
            ],
        ]);
};
