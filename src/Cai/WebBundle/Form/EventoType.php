<?php

namespace Cai\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventoType extends AbstractType
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
            ->add('fecha_inicio',DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy/MM/dd HH:mm',
            ))
            ->add('fecha_fin',DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy/MM/dd HH:mm',
            ))
            ->add('allDay')
            ->add('categoria')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cai\WebBundle\Entity\Evento'
        ));
    }
}
