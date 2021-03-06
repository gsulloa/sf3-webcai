<?php

namespace Cai\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',null,array(
                'label' => "Nombre (*)"
            ))
            ->add('apellido',null,array(
                'label' => "Apellido (*)"
            ))
            ->add('sexo',ChoiceType::class,array(
                'choices' => array(
                    'M' => "m",
                    'F' => "f"),
                'label' => "Sexo (*)"
            ))
            ->add('mail',EmailType::class,array(
                'label' =>  "Email (*)"
            ))
            ->add('celular')
            ->add('rut')
            ->add('file', null, array(
                'required' => false,
                'label' => "Imágen"
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cai\WebBundle\Entity\UserProfile'
        ));
    }
}
