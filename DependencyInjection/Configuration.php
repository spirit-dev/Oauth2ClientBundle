<?php

namespace SpiritDev\Bundle\OAuth2ClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('spirit_dev_o_auth2_client');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
            ->arrayNode('api_oauth_settings')
                ->children()
                    ->scalarNode('token_uri')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('get_user_uri')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('client_id')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('client_secret')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('redirect_uri')->isRequired()->cannotBeEmpty()->end()
                ->end()
            ->end()
            ->arrayNode('login_success_settings')
                ->children()
                    ->scalarNode('redirection_type')->isRequired()->cannotBeEmpty()->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
