<?php

/**
 * Email
 */

namespace Ephp\ACLBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CambiaEmailType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('email', 'email', array(
                    'read_only' => true
                ))
                ->add('email_nuova', 'email', array(
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ephp\ACLBundle\Entity\User'
        ));
    }

    public function getName() {
        return 'form_modifica_email';
    }

}
