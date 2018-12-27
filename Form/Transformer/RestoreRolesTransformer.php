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

namespace KR\SonataUserBundle\Form\Transformer;

use KR\SonataUserBundle\Security\EditableRolesBuilder;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author KR Digital <box@kr.digital>
 */
class RestoreRolesTransformer implements DataTransformerInterface
{
    /**
     * @var array|null
     */
    protected $originalRoles;

    /**
     * @var EditableRolesBuilder|null
     */
    protected $rolesBuilder;

    /**
     * @param EditableRolesBuilder $rolesBuilder
     */
    public function __construct(EditableRolesBuilder $rolesBuilder)
    {
        $this->rolesBuilder = $rolesBuilder;
    }

    /**
     * @param array|null $originalRoles
     */
    public function setOriginalRoles(array $originalRoles = null): void
    {
        $this->originalRoles = $originalRoles ?: [];
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if (null === $value) {
            return $value;
        }

        if (null === $this->originalRoles) {
            throw new \RuntimeException('Invalid state, originalRoles array is not set');
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($selectedRoles)
    {
        if (null === $this->originalRoles) {
            throw new \RuntimeException('Invalid state, originalRoles array is not set');
        }

        $availableRoles = $this->rolesBuilder->getRoles();

        $hiddenRoles = \array_diff($this->originalRoles, \array_keys($availableRoles));

        return \array_merge($selectedRoles, $hiddenRoles);
    }
}
