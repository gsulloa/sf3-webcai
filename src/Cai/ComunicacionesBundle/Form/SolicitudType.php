<?php

namespace Cai\ComunicacionesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('tipo', ChoiceType::class, array(
                'choices'   => array(
                    "Macroproyecto"     => 1,
                    "Importante"        => 2,
                    "Media Importancia" => 3,
                    "No tan importante" => 4
                )
            ))
            ->add('materiales',null,array(
                'expanded' => true
            ))
            ->add('descripcion')
            ->add('texto', null, array(
                'label' => "Informacion Importante"
            ))
            ->add('ideas')
            ->add('fecha', DateType::class)

            ->add('categoria', null , array(
                'required' => true
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cai\ComunicacionesBundle\Entity\Solicitud'
        ));
    }
}
