<?php

namespace Zent\VarsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Zent\VarsBundle\Entity\VarsManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ZentVarsExtension extends Extension
{
    /**
     * {@inheritDoc}
     * @throws BadMethodCallException
     * @throws InvalidArgumentException
     * @throws \Exception
     * @throws ServiceNotFoundException
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('zent_vars.class',$config['class']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
