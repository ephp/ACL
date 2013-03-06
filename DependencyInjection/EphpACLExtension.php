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

        $container->setParameter('ephp_acl.user.class', $config['user_class']);
        $container->setParameter('ephp_acl.user.namespace', '\\'.$config['user_class']);
        $container->setParameter('ephp_acl.access_log.enable', $config['access_log']['enable']);
        $container->setParameter('ephp_acl.access_log.class', $config['access_log']['class']);
        $container->setParameter('ephp_acl.access_log.namespace', '\\'.$config['access_log']['class']);
        $container->setParameter('ephp_acl.access_log.check_ip', $config['access_log']['check_ip']);
        foreach (array('app_id', 'app_secret', 'app_name', 'app_url', 'app_description') as $attribute) {
            $container->setParameter('ephp_acl.facebook.'.$attribute, $config['facebook'][$attribute]);
        }

        $loaderYml = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loaderYml->load('services.yml');
        
        $loaderXml = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loaderXml->load('orm.xml');
    }
    /*
    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (array_key_exists($name, $config)) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }

    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!array_key_exists($ns, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    $container->setParameter(sprintf($map, $name), $value);
                }
            }
        }
    }
     */
}
