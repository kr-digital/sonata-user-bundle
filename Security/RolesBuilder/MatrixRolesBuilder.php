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

namespace KR\SonataUserBundle\Security\RolesBuilder;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Silas Joisten <silasjoisten@hotmail.de>
 * @author KR Digital <box@gkr.digital>
 */
final class MatrixRolesBuilder implements MatrixRolesBuilderInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AdminRolesBuilderInterface
     */
    private $adminRolesBuilder;

    /**
     * @var ExpandableRolesBuilderInterface
     */
    private $securityRolesBuilder;

    public function __construct(TokenStorageInterface $tokenStorage, AdminRolesBuilderInterface $adminRolesBuilder, ExpandableRolesBuilderInterface $securityRolesBuilder)
    {
        $this->tokenStorage = $tokenStorage;
        $this->adminRolesBuilder = $adminRolesBuilder;
        $this->securityRolesBuilder = $securityRolesBuilder;
    }

    public function getRoles(?string $domain = null): array
    {
        if (!$this->tokenStorage->getToken()) {
            return [];
        }

        return \array_merge(
            $this->securityRolesBuilder->getRoles($domain),
            $this->adminRolesBuilder->getRoles($domain)
        );
    }

    public function getExpandedRoles(?string $domain = null): array
    {
        if (!$this->tokenStorage->getToken()) {
            return [];
        }

        return \array_merge(
            $this->securityRolesBuilder->getExpandedRoles($domain),
            $this->adminRolesBuilder->getRoles($domain)
        );
    }

    public function getPermissionLabels(): array
    {
        return $this->adminRolesBuilder->getPermissionLabels();
    }
}
