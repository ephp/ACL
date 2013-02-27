<?php

namespace Ephp\ACLBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EphpACLExtension extends Extension {

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('ephp_acl.user_repository', $config['user_class']);
        $container->setParameter('ephp_acl.user_class', '\\'.$config['user_class']);
        foreach (array('app_id', 'app_secret', 'app_name', 'app_url', 'app_description') as $attribute) {
            $container->setParameter('ephp_acl.facebook.'.$attribute, $config['facebook'][$attribute]);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

}
