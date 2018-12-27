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

namespace KR\SonataUserBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Christian Gripp <mail@core23.de>
 * @author Cengizhan Çalışkan <cengizhancaliskan@gmail.com>
 * @author Silas Joisten <silasjoisten@hotmail.de>
 * @author KR Digital <box@kr.digital>
 */
final class RolesMatrixCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('sonata.admin') as $name => $items) {
            foreach ($items as $item) {
                if (($item['show_in_roles_matrix'] ?? true) === false) {
                    $container->getDefinition('kr_sonata_user.admin_roles_builder')
                        ->addMethodCall('addExcludeAdmin', [$name]);
                }
            }
        }
    }
}
