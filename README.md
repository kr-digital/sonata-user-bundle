# KRSonataUserBundle

## Requirements

- php ^7.1.3
- symfony ^3.2
- sonata-project/admin-bundle ^3.1


```php
<?php

declare(strict_types=1);

namespace AppBundle\Admin;

use KR\SonataUserBundle\Form\Type\RolesMatrixType;
use KR\SonataUserBundle\Form\Type\SecurityRolesType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

// ...

class UserAdmin extends AbstractAdmin
{
    protected $translationDomain = 'UserAdmin';
    
    // ...
    
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('roles', RolesMatrixType::class /** SecurityRolesType::class */, [
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
        ;
        
        // ...
    }
}
```


Exclude resource.

Add `show_in_roles_matrix` into configuration

```yaml
    # app/config/services.yml

    # with sonata-project/doctrine-orm-admin-bundle
    app.admin.user:
        class: AppBundle\Admin\UserAdmin
        arguments: [~, AppBundle\Entity\User, ~]
        tags:
            - { name: sonata.admin, manager_type: orm, group: Users, label: User, icon: '<i class="fa fa-user"></i>', show_in_roles_matrix: false }
        public: true


```


