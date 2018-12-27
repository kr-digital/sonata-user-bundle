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

namespace KR\SonataUserBundle\Form\Type;

use KR\SonataUserBundle\Security\RolesBuilder\ExpandableRolesBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Silas Joisten <silasjoisten@hotmail.de>
 * @author KR Digital <box@gkr.digital>
 */
final class RolesMatrixType extends AbstractType
{
    /**
     * @var ExpandableRolesBuilderInterface
     */
    private $rolesBuilder;

    public function __construct(ExpandableRolesBuilderInterface $rolesBuilder)
    {
        $this->rolesBuilder = $rolesBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'expanded' => true,
            'choices' => function (Options $options, $parentChoices): array {
                if (!empty($parentChoices)) {
                    return [];
                }

                $roles = $this->rolesBuilder->getRoles(
                    $options['choice_translation_domain'],
                    $options['expanded']
                );
                $roles = \array_keys($roles);

                return \array_combine($roles, $roles);
            },
            'choice_translation_domain' => function (Options $options, $value): ?string {
                // if choice_translation_domain is true, then it's the same as translation_domain
                if (true === $value) {
                    $value = $options['translation_domain'];
                }
                if (null === $value) {
                    // no translation domain yet, try to ask sonata admin
                    $admin = null;
                    if (isset($options['sonata_admin'])) {
                        $admin = $options['sonata_admin'];
                    }
                    if (null === $admin && isset($options['sonata_field_description'])) {
                        $admin = $options['sonata_field_description']->getAdmin();
                    }
                    if (null !== $admin) {
                        $value = $admin->getTranslationDomain();
                    }
                }

                return $value;
            },

            'data_class' => null,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'kr_sonata_roles_matrix';
    }

    public function getName(): string
    {
        return $this->getBlockPrefix();
    }
}
