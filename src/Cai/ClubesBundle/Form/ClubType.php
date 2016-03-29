<?php

namespace Cai\ClubesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('hora')
            ->add('lugar')
            ->add('web')
            ->add('facebook')
            ->add('twitter')
            ->add('instagram')
            ->add('file', null, array(
                'required' => false,
                'label' => "Imágen"
            ))
            ->add('categoria')
            ->add('admin')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cai\ClubesBundle\Entity\Club'
        ));
    }
}
