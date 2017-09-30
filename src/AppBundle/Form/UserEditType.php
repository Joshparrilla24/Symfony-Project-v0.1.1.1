<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

Class UserEditType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            $builder
                ->add('username', TextType::class)
                ->add('email', EmailType::class)
                ->add('userrole', 'choices', [

                    'ROLE_ADMIN' => 'admin',
                    'ROLE_USER' => 'member',
                    'expandable'=> true,
                    'multiple' => true,


                ]);





    }


}

