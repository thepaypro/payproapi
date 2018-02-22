<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class AppExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('utils.yml');
        $loader->load('contact.yml');
        $loader->load('mobileVerificationCode.yml');
        $loader->load('notification.yml');
        $loader->load('subscribers.yml');
        $loader->load('user.yml');
        $loader->load('ethereum.yml');
        $loader->load('bitcoinTransaction.yml');
        $loader->load('bitcoinWallet.yml');
        $loader->load('bitcoinWalletApiClient.yml');

        if ($container->getParameter('bitcoin_mock')) {
            $loader->load('bitcoinWalletApiClientMock.yml');
        }
    }
}