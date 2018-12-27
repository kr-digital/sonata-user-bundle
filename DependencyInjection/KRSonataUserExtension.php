<?php

declare(strict_types=1);

/*
 * This file is part of the KRSonataUserBundle package.
 *
 * (c) KR Digital <box@gkr.digital>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * partially taken from https://github.com/sonata-project/SonataUserBundle
 *     SonataUserBundle is released under the MIT License. See https://github.com/sonata-project/SonataUserBundle/blob/4.x/LICENSE for details.
 *     (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */

namespace KR\SonataUserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author     Thomas Rabaix <thomas.rabaix@sonata-project.org>
 * @author KR Digital <box@kr.digital>
 */
class KRSonataUserExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        if ($container->hasExtension('twig')) {
            $container->prependExtensionConfig('twig', ['form_themes' => ['@KRSonataUser/Form/form_admin_fields.html.twig']]);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $bundles = $container->getParameter('kernel.bundles');
        if (!isset($bundles['SonataAdminBundle'])) {
            throw new \RuntimeException('SonataAdminBundle is required!');
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('admin.yaml');
    }
}
