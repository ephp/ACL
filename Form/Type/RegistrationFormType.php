<?php
/*---------------------------------- 
 * 
 *  Sovrascrivo il Form del FOS 
 *  doc: https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/doc/overriding_forms.md
 * 
 * ----------------------------------*/
namespace Ephp\ACLBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        //questo prende tutte le voci del form FOS
       parent::buildForm($builder, $options);       
       $builder->remove('username');  // we use email as the username

       //qui si possono aggiungere campi personalizzati
//       $builder 
//            ->add('gender', 'choice', array(
//                'label' => 'form.sesso',
//                'translation_domain' => 'FOSUserBundle',
//                'choices'   => array('m' => 'form.maschio', 'f' => 'form.femmina'),
//                'expanded' => true,
//                'required'  => true,
//                )
//            ->add('citta', null, array('label' => 'form.citta', 'translation_domain' => 'FOSUserBundle'))
//            ->add('yearOfBirth', 'date', array('label'  => 'form.compleanno',
//                    'translation_domain' => 'FOSUserBundle','widget' => 'choice', 'format' => 'dd-MM-yyyy','years' => range(1920, date('Y')-18))
//               );
           
    }

    public function getName()
    {
        return 'ephp_registration';
    }
}