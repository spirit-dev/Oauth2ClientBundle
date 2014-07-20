<?php

namespace SpiritDev\Bundle\OAuth2ClientBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SpiritDevOAuth2ClientExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = array();
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (!isset($config['token_uri'])) {
            throw new \InvalidArgumentException('The "token_uri" option must be set');
        }
        if (!isset($config['get_user_uri'])) {
            throw new \InvalidArgumentException('The "get_user_uri" option must be set');
        }
        if (!isset($config['client_id'])) {
            throw new \InvalidArgumentException('The "client_id" option must be set');
        }
        if (!isset($config['client_secret'])) {
            throw new \InvalidArgumentException('The "client_secret" option must be set');
        }
        if (!isset($config['redirect_uri'])) {
            throw new \InvalidArgumentException('The "redirect_uri" option must be set');
        }

        $container->setParameter('spirit_dev_o_auth2_client.token_uri', $config['token_uri']);
        $container->setParameter('spirit_dev_o_auth2_client.get_user_uri', $config['get_user_uri']);
        $container->setParameter('spirit_dev_o_auth2_client.client_id', $config['client_id']);
        $container->setParameter('spirit_dev_o_auth2_client.client_secret', $config['client_secret']);
        $container->setParameter('spirit_dev_o_auth2_client.redirect_uri', $config['redirect_uri']);
    }

    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/';
    }

    public function getNamespace()
    {
        return 'http://www.example.com/symfony/schema/';
    }
}